<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $posts = Post::where('user_id', auth()->id())
            ->with('products')
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $products = Product::all();
        return view('posts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
        ]);

        $imagePath = $request->file('image')->store('posts', 'public');

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePath,
        ]);

        $post->products()->attach($validated['products']);

        return redirect()->route('posts.index')->with('success', __('post_created'));
    }

    public function show(Post $post)
    {
        $post->increment('views');
        $post->load('user', 'products');

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $products = Product::all();

        return view('posts.edit', compact('post', 'products'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($post->image);
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);
        $post->products()->sync($validated['products']);

        return redirect()->route('posts.index')->with('success', __('post_updated'));
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        Storage::disk('public')->delete($post->image);
        $post->delete();

        return redirect()->route('posts.index')->with('success', __('post_deleted'));
    }
}