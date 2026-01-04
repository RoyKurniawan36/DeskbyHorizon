<?php

namespace App\Http\Controllers;

use App\Models\Post;

class WelcomeController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'products')
            ->latest()
            ->paginate(12);

        return view('welcome', compact('posts'));
    }
}