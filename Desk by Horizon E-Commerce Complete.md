# Desk by Horizon - Complete Laravel 12 E-Commerce Platform

A comprehensive, production-ready guide to building a multi-vendor desk setup e-commerce platform with shop owners, order approval systems, and modern features.

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Initial Setup](#initial-setup)
3. [Database Configuration](#database-configuration)
4. [Database Migrations](#database-migrations)
5. [Models](#models)
6. [Controllers](#controllers)
7. [Policies & Authorization](#policies--authorization)
8. [Routes](#routes)
9. [Views](#views)
10. [Language & Localization](#language--localization)
11. [Middleware](#middleware)
12. [Admin Panel (Backpack CRUD)](#admin-panel-backpack-crud)
13. [Testing & Deployment](#testing--deployment)
14. [Troubleshooting](#troubleshooting)

---

## Project Overview

### Features

-   ✅ User Authentication (Combined Login/Register)
-   ✅ Desk Setup Posts (Users share their setups)
-   ✅ Multi-Vendor Shop System (Shop owners manage stores)
-   ✅ E-Commerce with Shopping Cart
-   ✅ Order Management with Shop Approval System
-   ✅ Admin Panel (Backpack CRUD)
-   ✅ Dark Mode & Light Mode
-   ✅ Multi-Language Support (English & Indonesian)
-   ✅ Responsive Design (Mobile-friendly)
-   ✅ Image Upload & Storage

### Technology Stack

-   **Framework**: Laravel 12
-   **Database**: MySQL
-   **Frontend**: Blade Templates
-   **CSS**: Tailwind CSS
-   **JavaScript**: Alpine.js
-   **Admin Panel**: Backpack for Laravel
-   **Authentication**: Laravel Breeze

---

## Initial Setup

### Create Laravel Project

```bash
composer create-project laravel/laravel desk-by-horizon
cd desk-by-horizon
```

### Install Required Packages

```bash
# Authentication
composer require laravel/breeze
php artisan breeze:install blade

# Admin Panel
composer require backpack/crud
php artisan backpack:install

# Image Processing (optional)
composer require intervention/image

# Frontend
npm install
npm install -D tailwindcss postcss autoprefixer
npm install alpinejs
npm run build
```

---

## Database Configuration

### Edit .env File

```bash
code .env
```

Update these values:

```env
APP_NAME="Desk by Horizon"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desk_by_horizon
DB_USERNAME=root
DB_PASSWORD=
```

### Create Database

```bash
# In MySQL or phpMyAdmin
CREATE DATABASE desk_by_horizon;
```

---

## Database Migrations

### Create Migration Files

```bash
# User roles
php artisan make:migration add_role_to_users_table

# Posts and relationships
php artisan make:migration create_posts_table
php artisan make:migration create_post_products_table

# Products
php artisan make:migration create_products_table
php artisan make:migration add_shop_id_to_products_table

# Shops
php artisan make:migration create_shops_table

# Cart and Orders
php artisan make:migration create_cart_items_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table

# Order Approvals
php artisan make:migration create_order_approvals_table

```

### Migration: Add Role to Users

**File: `database/migrations/xxxx_add_role_to_users_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'shop_owner', 'admin'])->default('user')->after('email');
            $table->string('avatar')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'avatar']);
        });
    }
};
```

### Migration: Create Posts Table

**File: `database/migrations/xxxx_create_posts_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('image');
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

### Migration: Create Products Table

**File: `database/migrations/xxxx_create_products_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

### Migration: Create Post-Products Relationship Table

**File: `database/migrations/xxxx_create_post_products_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_products');
    }
};
```

### Migration: Create Shops Table

**File: `database/migrations/xxxx_create_shops_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
```

### Migration: Create Cart Items Table

**File: `database/migrations/xxxx_create_cart_items_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
```

### Migration: Create Orders Table

**File: `database/migrations/xxxx_create_orders_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'approved', 'paid', 'shipped', 'delivered'])->default('pending');
            $table->text('shipping_address');
            $table->enum('payment_method', ['cash_on_delivery', 'bank_transfer'])->default('cash_on_delivery');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
```

### Migration: Create Order Items Table

**File: `database/migrations/xxxx_create_order_items_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
```

### Migration: Create Order Approvals Table

**File: `database/migrations/xxxx_create_order_approvals_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_approvals');
    }
};
```

### Run All Migrations

```bash
php artisan migrate
```

---

## Models

### Create Models

```bash
php artisan make:model Shop
php artisan make:model OrderApproval
```

### User Model

**File: `app/Models/User.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isShopOwner()
    {
        return $this->role === 'shop_owner';
    }

    public function hasShop()
    {
        return $this->shop()->exists();
    }

    public function getInitials()
    {
        $names = explode(' ', $this->name);
        if (count($names) >= 2) {
            return strtoupper(substr($names[0], 0, 1) . substr($names[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }
}
```

### Post Model

**File: `app/Models/Post.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image',
        'views',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'post_products');
    }
}
```

### Product Model

**File: `app/Models/Product.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'price',
        'image',
        'stock',
        'category',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_products');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
```

### Shop Model

**File: `app/Models/Shop.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'logo',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($shop) {
            if (empty($shop->slug)) {
                $shop->slug = Str::slug($shop->name);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orderApprovals()
    {
        return $this->hasMany(OrderApproval::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
```

### CartItem Model

**File: `app/Models/CartItem.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
```

### Order Model

**File: `app/Models/Order.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'status',
        'shipping_address',
        'payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function approvals()
    {
        return $this->hasMany(OrderApproval::class);
    }

    public function isFullyApproved()
    {
        $requiredShops = $this->items()
            ->with('product.shop')
            ->get()
            ->pluck('product.shop_id')
            ->unique()
            ->filter();

        if ($requiredShops->isEmpty()) {
            return true;
        }

        $approvedShops = $this->approvals()
            ->where('is_approved', true)
            ->pluck('shop_id');

        return $requiredShops->diff($approvedShops)->isEmpty();
    }

    public function needsApprovalFrom()
    {
        $requiredShops = $this->items()
            ->with('product.shop')
            ->get()
            ->pluck('product.shop_id')
            ->unique()
            ->filter();

        $approvedShops = $this->approvals()
            ->where('is_approved', true)
            ->pluck('shop_id');

        return Shop::whereIn('id', $requiredShops->diff($approvedShops))->get();
    }

    public static function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }
}
```

### OrderItem Model

**File: `app/Models/OrderItem.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
```

### OrderApproval Model

**File: `app/Models/OrderApproval.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shop_id',
        'is_approved',
        'approved_at',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
```

---

## Controllers

### WelcomeController

**File: `app/Http/Controllers/WelcomeController.php`**

```php
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
```

### PostController

**File: `app/Http/Controllers/PostController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
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

        return redirect()->route('posts.index')->with('success', __('messages.post_created'));
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

        return redirect()->route('posts.index')->with('success', __('messages.post_updated'));
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        Storage::disk('public')->delete($post->image);
        $post->delete();

        return redirect()->route('posts.index')->with('success', __('messages.post_deleted'));
    }
}
```

### CartController

**File: `app/Http/Controllers/CartController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function addProduct(Request $request, Product $product)
    {
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return redirect()->back()->with('success', __('messages.added_to_cart'));
    }

    public function addFromPost(Post $post)
    {
        $products = $post->products;

        foreach ($products as $product) {
            $cartItem = CartItem::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }
        }

        return redirect()->route('cart.index')->with('success', __('messages.products_added_to_cart'));
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorize('update', $cartItem);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update($validated);

        return redirect()->back()->with('success', __('messages.cart_updated'));
    }

    public function destroy(CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);
        $cartItem->delete();

        return redirect()->back()->with('success', __('messages.removed_from_cart'));
    }
}
```

### CheckoutController

**File: `app/Http/Controllers/CheckoutController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty'));
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string|in:cash_on_delivery,bank_transfer',
        ]);

        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product.shop')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty'));
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        DB::transaction(function () use ($validated, $cartItems, $total) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'total' => $total,
                'status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
            ]);

            // Create order approvals for each shop
            $shopIds = $cartItems->pluck('product.shop_id')->unique()->filter();
            foreach ($shopIds as $shopId) {
                OrderApproval::create([
                    'order_id' => $order->id,
                    'shop_id' => $shopId,
                    'is_approved' => false,
                ]);
            }

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }

            CartItem::where('user_id', auth()->id())->delete();
        });

        return redirect()->route('orders.index')->with('success', __('messages.order_placed'));
    }
}
```

### OrderController

**File: `app/Http/Controllers/OrderController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product.shop', 'approvals.shop')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['items.product.shop', 'approvals.shop']);

        return view('orders.show', compact('order'));
    }
}
```

### ShopController

**File: `app/Http/Controllers/ShopController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::where('is_active', true)
            ->with('user')
            ->withCount('products')
            ->latest()
            ->paginate(12);

        return view('shops.index', compact('shops'));
    }

    public function show(Shop $shop)
    {
        $shop->load('user', 'products');
        return view('shops.show', compact('shop'));
    }

    public function create()
    {
        $this->authorize('create', Shop::class);
        return view('shops.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Shop::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('shops', 'public');
        }

        Shop::create($validated);

        return redirect()->route('shop-owner.dashboard')->with('success', __('messages.shop_created'));
    }

    public function edit(Shop $shop)
    {
        $this->authorize('update', $shop);
        return view('shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        $this->authorize('update', $shop);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            $validated['logo'] = $request->file('logo')->store('shops', 'public');
        }

        $shop->update($validated);

        return redirect()->route('shop-owner.dashboard')->with('success', __('messages.shop_updated'));
    }

    public function destroy(Shop $shop)
    {
        $this->authorize('delete', $shop);

        if ($shop->logo) {
            Storage::disk('public')->delete($shop->logo);
        }

        $shop->delete();

        return redirect()->route('shops.index')->with('success', __('messages.shop_deleted'));
    }
}
```

### ShopOwnerController

**File: `app/Http/Controllers/ShopOwnerController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopOwnerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user->hasShop()) {
            return redirect()->route('shops.create')->with('info', __('messages.create_shop_first'));
        }

        $shop = $user->shop;
        $products = $shop->products()->latest()->paginate(10);
        $pendingApprovals = OrderApproval::where('shop_id', $shop->id)
            ->where('is_approved', false)
            ->with('order.user')
            ->latest()
            ->paginate(10);

        return view('shop-owner.dashboard', compact('shop', 'products', 'pendingApprovals'));
    }

    public function products()
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return redirect()->route('shops.create');
        }

        $products = $shop->products()->latest()->paginate(15);

        return view('shop-owner.products.index', compact('shop', 'products'));
    }

    public function createProduct()
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return redirect()->route('shops.create');
        }

        return view('shop-owner.products.create', compact('shop'));
    }

    public function storeProduct(Request $request)
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return redirect()->route('shops.create');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['shop_id'] = $shop->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('shop-owner.products')->with('success', __('messages.product_created'));
    }

    public function editProduct(Product $product)
    {
        $this->authorize('update', $product);
        $shop = auth()->user()->shop;

        return view('shop-owner.products.edit', compact('shop', 'product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('shop-owner.products')->with('success', __('messages.product_updated'));
    }

    public function destroyProduct(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('shop-owner.products')->with('success', __('messages.product_deleted'));
    }

    public function orders()
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return redirect()->route('shops.create');
        }

        $approvals = OrderApproval::where('shop_id', $shop->id)
            ->with(['order.user', 'order.items.product'])
            ->latest()
            ->paginate(15);

        return view('shop-owner.orders', compact('shop', 'approvals'));
    }

    public function approveOrder(Request $request, OrderApproval $approval)
    {
        $shop = auth()->user()->shop;

        if ($approval->shop_id !== $shop->id) {
            abort(403);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $approval->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        $order = $approval->order;
        if ($order->isFullyApproved()) {
            $order->update(['status' => 'approved']);
        }

        return redirect()->back()->with('success', __('messages.order_approved'));
    }
}
```

### LanguageController

**File: `app/Http/Controllers/LanguageController.php`**

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        if (in_array($locale, ['en', 'id'])) {
            Session::put('locale', $locale);
            app()->setLocale($locale);
        }

        return redirect()->back();
    }
}
```

---

## Policies & Authorization

### PostPolicy

**File: `app/Policies/PostPolicy.php`**

```php
<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}
```

### CartItemPolicy

**File: `app/Policies/CartItemPolicy.php`**

```php
<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;

class CartItemPolicy
{
    public function update(User $user, CartItem $cartItem): bool
    {
        return $user->id === $cartItem->user_id;
    }

    public function delete(User $user, CartItem $cartItem): bool
    {
        return $user->id === $cartItem->user_id;
    }
}
```

### OrderPolicy

**File: `app/Policies/OrderPolicy.php`**

```php
<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->isAdmin();
    }
}
```

### ShopPolicy

**File: `app/Policies/ShopPolicy.php`**

```php
<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Shop $shop): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isShopOwner() && !$user->hasShop();
    }

    public function update(User $user, Shop $shop): bool
    {
        return $user->id === $shop->user_id;
    }

    public function delete(User $user, Shop $shop): bool
    {
        return $user->id === $shop->user_id || $user->isAdmin();
    }
}
```

### ProductPolicy

**File: `app/Policies/ProductPolicy.php`**

```php
<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isShopOwner() && $user->hasShop();
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$product->shop_id) {
            return false;
        }

        return $user->isShopOwner() && $product->shop->user_id === $user->id;
    }

    public function delete(User $user, Product $product): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$product->shop_id) {
            return false;
        }

        return $user->isShopOwner() && $product->shop->user_id === $user->id;
    }
}
```

---

## Routes

**File: `routes/web.php`**

```php
<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopOwnerController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/auth', fn() => view('auth.combined.auth'))->name('auth.combined');
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Shop routes (public)
Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Posts
    Route::resource('posts', PostController::class);

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/product/{product}', [CartController::class, 'addProduct'])->name('cart.addProduct');
    Route::post('/cart/post/{post}', [CartController::class, 'addFromPost'])->name('cart.addFromPost');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout & Orders
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Shops management
    Route::get('/shops/create', [ShopController::class, 'create'])->name('shops.create');
    Route::post('/shops', [ShopController::class, 'store'])->name('shops.store');
    Route::get('/shops/{shop}/edit', [ShopController::class, 'edit'])->name('shops.edit');
    Route::patch('/shops/{shop}', [ShopController::class, 'update'])->name('shops.update');
    Route::delete('/shops/{shop}', [ShopController::class, 'destroy'])->name('shops.destroy');

    // Shop Owner Dashboard
    Route::prefix('shop-owner')->name('shop-owner.')->group(function () {
        Route::get('/dashboard', [ShopOwnerController::class, 'dashboard'])->name('dashboard');

        // Products
        Route::get('/products', [ShopOwnerController::class, 'products'])->name('products');
        Route::get('/products/create', [ShopOwnerController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [ShopOwnerController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [ShopOwnerController::class, 'editProduct'])->name('products.edit');
        Route::patch('/products/{product}', [ShopOwnerController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [ShopOwnerController::class, 'destroyProduct'])->name('products.destroy');

        // Orders
        Route::get('/orders', [ShopOwnerController::class, 'orders'])->name('orders');
        Route::post('/orders/{approval}/approve', [ShopOwnerController::class, 'approveOrder'])->name('orders.approve');
    });
});

require __DIR__.'/auth.php';
```

---

## Views

Due to character limits, key views are:

-   `resources/views/auth/combined/auth.blade.php` - Combined login/register
-   `resources/views/layouts/app.blade.php` - Main layout
-   `resources/views/welcome.blade.php` - Homepage
-   `resources/views/posts/{index,create,edit,show}.blade.php` - Post views
-   `resources/views/cart/index.blade.php` - Shopping cart
-   `resources/views/checkout/index.blade.php` - Checkout
-   `resources/views/orders/{index,show}.blade.php` - Orders
-   `resources/views/shops/{index,show,create,edit}.blade.php` - Shop views
-   `resources/views/shop-owner/dashboard.blade.php` - Shop owner dashboard
-   `resources/views/shop-owner/{products,orders}/`.blade.php` - Shop management

Create directories:

```bash
mkdir resources/views/auth/combined
mkdir resources/views/posts
mkdir resources/views/cart
mkdir resources/views/checkout
mkdir resources/views/orders
mkdir resources/views/shops
mkdir resources/views/shop-owner/products

```

### resources/views/auth/combined/auth.blade.php

```html
<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
    x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))"
    :class="{ 'dark': darkMode }"
>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ __('auth.title') }} - {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="font-sans antialiased bg-gradient-to-br from-background-50 to-background-100 dark:from-background-900 dark:to-background-800 transition-colors duration-200"
    >
        <div
            class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4"
        >
            <!-- Logo -->
            <div class="mb-8">
                <a href="/">
                    <h1
                        class="text-4xl font-bold text-background-900 dark:text-text-50"
                    >
                        Desk by Horizon
                    </h1>
                </a>
            </div>

            <!-- Auth Card -->
            <div
                class="w-full sm:max-w-md bg-background-50 dark:bg-background-800 shadow-2xl rounded-2xl overflow-hidden transition-colors duration-200"
            >
                <!-- Tab Navigation -->
                <div
                    class="flex border-b border-background-200 dark:border-background-700"
                    x-data="{ activeTab: '{{ request('mode') === 'register' ? 'register' : 'login' }}' }"
                >
                    <button
                        @click="activeTab = 'login'"
                        :class="activeTab === 'login' ? 'border-b-2 border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'text-text-600 dark:text-text-400'"
                        class="flex-1 py-4 px-6 text-center font-semibold transition-all duration-200 hover:bg-background-50 dark:hover:bg-background-700"
                    >
                        {{ __('auth.login') }}
                    </button>
                    <button
                        @click="activeTab = 'register'"
                        :class="activeTab === 'register' ? 'border-b-2 border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400' : 'text-text-600 dark:text-text-400'"
                        class="flex-1 py-4 px-6 text-center font-semibold transition-all duration-200 hover:bg-background-50 dark:hover:bg-background-700"
                    >
                        {{ __('auth.register') }}
                    </button>
                </div>

                <!-- Login Form -->
                <div x-show="activeTab === 'login'" x-transition class="p-8">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <label
                                for="login-email"
                                class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2"
                                >{{ __('auth.email') }}</label
                            >
                            <input
                                id="login-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full px-4 py-3 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition-all duration-200"
                            />
                            @error('email')
                            <p
                                class="mt-2 text-sm text-accent-600 dark:text-accent-400"
                            >
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label
                                for="login-password"
                                class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2"
                                >{{ __('auth.password') }}</label
                            >
                            <input
                                id="login-password"
                                type="password"
                                name="password"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition-all duration-200"
                            />
                            @error('password')
                            <p
                                class="mt-2 text-sm text-accent-600 dark:text-accent-400"
                            >
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-background-300 dark:border-background-600 text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-400"
                                />
                                <span
                                    class="ml-2 text-sm text-text-600 dark:text-text-400"
                                    >{{ __('auth.remember') }}</span
                                >
                            </label>
                            @if (Route::has('password.request'))
                            <a
                                href="{{ route('password.request') }}"
                                class="text-sm text-primary-600 dark:text-primary-400 hover:underline"
                                >{{ __('auth.forgot') }}</a
                            >
                            @endif
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105"
                        >
                            {{ __('auth.login') }}
                        </button>
                    </form>
                </div>

                <!-- Register Form -->
                <div x-show="activeTab === 'register'" x-transition class="p-8">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-4">
                            <label
                                for="register-name"
                                class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2"
                                >{{ __('auth.name') }}</label
                            >
                            <input
                                id="register-name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition-all duration-200"
                            />
                        </div>

                        <div class="mb-4">
                            <label
                                for="register-email"
                                class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2"
                                >{{ __('auth.email') }}</label
                            >
                            <input
                                id="register-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition-all duration-200"
                            />
                        </div>

                        <div class="mb-4">
                            <label
                                for="register-password"
                                class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2"
                                >{{ __('auth.password') }}</label
                            >
                            <input
                                id="register-password"
                                type="password"
                                name="password"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition-all duration-200"
                            />
                        </div>

                        <div class="mb-6">
                            <label
                                for="register-password-confirmation"
                                class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2"
                                >{{ __('auth.confirm_password') }}</label
                            >
                            <input
                                id="register-password-confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition-all duration-200"
                            />
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105"
                        >
                            {{ __('auth.register') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
```

### resources/views/layouts/app.blade.php - Main layout

```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light')); if (!localStorage.getItem('theme')) { darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches; }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-background-100 dark:bg-background-900 transition-colors duration-200">
    <!-- Navigation -->
    <nav class="bg-background-50 dark:bg-background-800 shadow-lg transition-colors duration-200" x-data="{ open: false, profileOpen: false }">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                    Desk by Horizon
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('welcome') }}" class="text-background-700 dark:text-text-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                        {{ __('messages.home') }}
                    </a>
                    @auth
                        <a href="{{ route('posts.index') }}" class="text-background-700 dark:text-text-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                            {{ __('messages.my_posts') }}
                        </a>
                        <a href="{{ route('cart.index') }}" class="text-background-700 dark:text-text-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 relative">
                            {{ __('messages.cart') }}
                            @if(auth()->user()->cartItems->count() > 0)
                                <span class="absolute -top-2 -right-2 bg-accent-500 text-text-50 text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ auth()->user()->cartItems->count() }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('orders.index') }}" class="text-background-700 dark:text-text-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                            {{ __('messages.orders') }}
                        </a>
                    @endauth

                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-2 border-l border-background-300 dark:border-background-600 pl-6">
                        <a href="{{ route('language.switch', 'en') }}" class="px-3 py-1 rounded {{ app()->getLocale() == 'en' ? 'bg-primary-600 text-text-50' : 'text-background-700 dark:text-text-300 hover:bg-background-100 dark:hover:bg-background-700' }} transition-all duration-200">
                            EN
                        </a>
                        <a href="{{ route('language.switch', 'id') }}" class="px-3 py-1 rounded {{ app()->getLocale() == 'id' ? 'bg-primary-600 text-text-50' : 'text-background-700 dark:text-text-300 hover:bg-background-100 dark:hover:bg-background-700' }} transition-all duration-200">
                            ID
                        </a>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode" class="p-2 rounded-lg bg-background-200 dark:bg-background-700 text-background-800 dark:text-background-200 hover:bg-background-300 dark:hover:bg-background-600 transition-all duration-200">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>

                    @guest
                        <a href="{{ route('auth.combined') }}" class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-6 py-2 rounded-lg transition-all duration-200">
                            {{ __('messages.login') }}
                        </a>
                    @else
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-primary-600">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-text-50 font-semibold">
                                        {{ auth()->user()->getInitials() }}
                                    </div>
                                @endif
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-background-50 dark:bg-background-800 rounded-lg shadow-xl py-2 z-50">
                                <div class="px-4 py-2 border-b border-background-200 dark:border-background-700">
                                    <p class="text-sm font-semibold text-background-900 dark:text-text-50">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-text-600 dark:text-text-400">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-background-700 dark:text-text-300 hover:bg-background-100 dark:hover:bg-background-700">
                                    {{ __('messages.profile') }}
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ url('admin') }}" class="block px-4 py-2 text-sm text-background-700 dark:text-text-300 hover:bg-background-100 dark:hover:bg-background-700">
                                        {{ __('messages.admin_panel') }}
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-accent-600 dark:text-accent-400 hover:bg-background-100 dark:hover:bg-background-700">
                                        {{ __('messages.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="md:hidden p-2 text-background-700 dark:text-text-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="open" x-transition class="md:hidden pb-4">
                <a href="{{ route('welcome') }}" class="block py-2 text-background-700 dark:text-text-300 hover:text-primary-600 dark:hover:text-primary-400">
                    {{ __('messages.home') }}
                </a>
                @auth
                    <a href="{{ route('posts.index') }}" class="block py-2 text-background-700 dark:text-text-300 hover:text-primary-600 dark:hover:text-primary-400">
                        {{ __('messages.my_posts') }}
                    </a>
                    <a href="{{ route('cart.index') }}" class="block py-2 text-background-700 dark:text-text-300 hover:text-primary-600 dark:hover:text-primary-400">
                        {{ __('messages.cart') }}
                    </a>
                    <a href="{{ route('orders.index') }}" class="block py-2 text-background-700 dark:text-text-300 hover:text-primary-600 dark:hover:text-primary-400">
                        {{ __('messages.orders') }}
                    </a>
                @else
                    <a href="{{ route('auth.combined') }}" class="block py-2 text-background-700 dark:text-text-300 hover:text-primary-600 dark:hover:text-primary-400">
                        {{ __('messages.login') }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-accent-100 dark:bg-accent-900 border border-accent-400 dark:border-accent-700 text-accent-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-background-50 dark:bg-background-800 mt-16 py-8 transition-colors duration-200">
        <div class="container mx-auto px-4 text-center text-text-600 dark:text-text-400">
            <p>© {{ date('Y') }} Desk by Horizon. {{ __('messages.all_rights_reserved') }}</p>
        </div>
    </footer>
</body>
</html>
```

### resources/views/welcome.blade.php

```php
@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-primary-600 to-secondary-600 dark:from-primary-800 dark:to-secondary-800 text-text-50 py-20 transition-colors duration-200">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-6">{{ __('messages.welcome_title') }}</h1>
        <p class="text-xl md:text-2xl mb-8 opacity-90">{{ __('messages.welcome_subtitle') }}</p>
        @guest
            <a href="{{ route('auth.combined') }}" class="bg-background-50 text-primary-600 px-8 py-3 rounded-full font-semibol hover:bg-background-100 transition-all duration-200 inline-block transform hover:scale-105">
                {{ __('messages.get_started') }}
            </a>
        @endguest
    </div>
</div>

<!-- Posts Grid -->
<div class="container mx-auto px-4 py-16">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-background-900 dark:text-text-50">{{ __('messages.featured_setups') }}</h2>
        @auth
            <a href="{{ route('posts.create') }}" class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-6 py-2 rounded-lg transition-all duration-200">
                {{ __('messages.share_setup') }}
            </a>
        @endauth
    </div>

    @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($posts as $post)
                <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg overflow-hidden transform transition-all duration-200 hover:scale-105 hover:shadow-2xl">
                    <div class="relative">
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-text-50 px-3 py-1 rounded-full text-sm">
                            {{ $post->views }} {{ __('messages.views') }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-background-900 dark:text-text-50 mb-2 truncate">{{ $post->title }}</h3>
                        <p class="text-sm text-text-600 dark:text-text-400 mb-3 line-clamp-2">{{ $post->description }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-text-50 text-xs font-semibold mr-2">
                                    {{ $post->user->getInitials() }}
                                </div>
                                <span class="text-sm text-background-700 dark:text-text-300">{{ $post->user->name }}</span>
                            </div>
                            <span class="text-sm text-primary-600 dark:text-primary-400 font-semibold">{{ $post->products->count() }} {{ __('messages.products') }}</span>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('posts.show', $post) }}" class="flex-1 bg-background-100 dark:bg-background-700 text-background-800 dark:text-text-50 text-center py-2 rounded-lg hover:bg-background-200 dark:hover:bg-background-600 transition-all duration-200">
                                {{ __('messages.view_details') }}
                            </a>
                            @auth
                                <form action="{{ route('cart.addFromPost', $post) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 py-2 rounded-lg transition-all duration-200">
                                        {{ __('messages.add_all') }}
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-text-600 dark:text-text-400 text-xl">{{ __('messages.no_posts_yet') }}</p>
        </div>
    @endif
</div>
@endsection
```

### resources/views/posts/{index,create,edit,show}.blade.php

-   index.blade.php:

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-background-900 dark:text-text-50">{{ __('messages.my_posts') }}</h1>
            <a href="{{ route('posts.create') }}" class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-6 py-2 rounded-lg transition-all duration-200">
                {{ __('messages.create_post') }}
            </a>
        </div>

        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg overflow-hidden">
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-background-900 dark:text-text-50 mb-2">{{ $post->title }}</h3>
                            <p class="text-sm text-text-600 dark:text-text-400 mb-3">{{ Str::limit($post->description, 100) }}</p>
                            <div class="flex items-center justify-between text-sm text-text-600 dark:text-text-400 mb-4">
                                <span>{{ $post->views }} {{ __('messages.views') }}</span>
                                <span>{{ $post->products->count() }} {{ __('messages.products') }}</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('posts.show', $post) }}" class="flex-1 text-center bg-background-100 dark:bg-background-700 text-background-800 dark:text-text-50 py-2 rounded-lg hover:bg-background-200 dark:hover:bg-background-600 transition-all duration-200">
                                    {{ __('messages.view') }}
                                </a>
                                <a href="{{ route('posts.edit', $post) }}" class="flex-1 text-center bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 py-2 rounded-lg transition-all duration-200">
                                    {{ __('messages.edit') }}
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-accent-600 hover:bg-accent-700 dark:bg-accent-500 dark:hover:bg-accent-600 text-text-50 px-4 py-2 rounded-lg transition-all duration-200">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-background-50 dark:bg-background-800 rounded-xl">
                <p class="text-text-600 dark:text-text-400 text-xl mb-4">{{ __('messages.no_posts_yet') }}</p>
                <a href="{{ route('posts.create') }}" class="inline-block bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-6 py-2 rounded-lg transition-all duration-200">
                    {{ __('messages.create_first_post') }}
                </a>
            </div>
        @endif
    </div>
    @endsection
    ```

-   create.blade.php:

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.create_post') }}</h1>

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.title') }}</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                @error('title')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.description') }}</label>
                <textarea name="description" id="description" rows="4" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.image') }}</label>
                <input type="file" name="image" id="image" accept="image/*" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50">
                @error('image')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.select_products') }}</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto border border-background-300 dark:border-background-600 rounded-lg p-4">
                    @foreach($products as $product)
                        <label class="flex items-center p-3 bg-background-50 dark:bg-background-700 rounded-lg hover:bg-background-100 dark:hover:bg-background-600 cursor-pointer">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}" class="mr-3 rounded border-background-300 dark:border-background-600 text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-400">
                            <div class="flex-1">
                                <p class="font-semibold text-background-900 dark:text-text-50">{{ $product->name }}</p>
                                <p class="text-sm text-text-600 dark:text-text-400">${{ number_format($product->price, 2) }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('products')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <a href="{{ route('posts.index') }}" class="flex-1 text-center bg-background-200 dark:bg-background-700 text-background-800 dark:text-text-50 py-2 rounded-lg hover:bg-background-300 dark:hover:bg-background-600 transition-all duration-200">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 py-2 rounded-lg transition-all duration-200">
                    {{ __('messages.create') }}
                </button>
            </div>
        </form>
    </div>
    @endsection
    ```

-   edit.blade.php:

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.edit_post') }}</h1>

        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.title') }}</label>
                <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                @error('title')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.description') }}</label>
                <textarea name="description" id="description" rows="4" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">{{ old('description', $post->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.current_image') }}</label>
                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover rounded-lg mb-2">
                <label for="image" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.new_image') }} ({{ __('messages.optional') }})</label>
                <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50">
                @error('image')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.select_products') }}</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto border border-background-300 dark:border-background-600 rounded-lg p-4">
                    @foreach($products as $product)
                        <label class="flex items-center p-3 bg-background-50 dark:bg-background-700 rounded-lg hover:bg-background-100 dark:hover:bg-background-600 cursor-pointer">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}"
                                   {{ in_array($product->id, $post->products->pluck('id')->toArray()) ? 'checked' : '' }}
                                   class="mr-3 rounded border-background-300 dark:border-background-600 text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-400">
                            <div class="flex-1">
                                <p class="font-semibold text-background-900 dark:text-text-50">{{ $product->name }}</p>
                                <p class="text-sm text-text-600 dark:text-text-400">${{ number_format($product->price, 2) }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('products')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <a href="{{ route('posts.index') }}" class="flex-1 text-center bg-background-200 dark:bg-background-700 text-background-800 dark:text-text-50 py-2 rounded-lg hover:bg-background-300 dark:hover:bg-background-600 transition-all duration-200">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 py-2 rounded-lg transition-all duration-200">
                    {{ __('messages.update') }}
                </button>
            </div>
        </form>
    </div>
    @endsection
    ```

-   show.blade.php:

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg overflow-hidden">
            <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-96 object-cover">

            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold text-background-900 dark:text-text-50 mb-4">{{ $post->title }}</h1>
                        <div class="flex items-center gap-4 text-text-600 dark:text-text-400">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-text-50 font-semibold mr-2">
                                    {{ $post->user->getInitials() }}
                                </div>
                                <span>{{ __('messages.posted_by') }} <strong>{{ $post->user->name }}</strong></span>
                            </div>
                            <span>•</span>
                            <span>{{ $post->created_at->format('M d, Y') }}</span>
                            <span>•</span>
                            <span>{{ $post->views }} {{ __('messages.views') }}</span>
                        </div>
                    </div>
                    @auth
                        @if(auth()->id() === $post->user_id)
                            <div class="flex gap-2">
                                <a href="{{ route('posts.edit', $post) }}" class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-4 py-2 rounded-lg transition-all duration-200">
                                    {{ __('messages.edit') }}
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-accent-600 hover:bg-accent-700 dark:bg-accent-500 dark:hover:bg-accent-600 text-text-50 px-4 py-2 rounded-lg transition-all duration-200">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                <div class="prose dark:prose-invert max-w-none mb-8">
                    <p class="text-background-700 dark:text-text-300 text-lg leading-relaxed">{{ $post->description }}</p>
                </div>

                <div class="border-t border-background-200 dark:border-background-700 pt-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-background-900 dark:text-text-50">
                            {{ __('messages.products_used') }} ({{ $post->products->count() }})
                        </h2>
                        @auth
                            <form action="{{ route('cart.addFromPost', $post) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-6 py-3 rounded-lg transition-all duration-200">
                                    {{ __('messages.add_all') }}
                                </button>
                            </form>
                        @endauth
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($post->products as $product)
                            <div class="bg-background-50 dark:bg-background-700 rounded-xl p-4 hover:shadow-lg transition-all duration-200">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-40 object-cover rounded-lg mb-4">
                                @endif
                                <h3 class="font-semibold text-background-900 dark:text-text-50 mb-2">{{ $product->name }}</h3>
                                <p class="text-sm text-text-600 dark:text-text-400 mb-3">{{ Str::limit($product->description, 80) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-primary-600 dark:text-primary-400">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                    @auth
                                        <form action="{{ route('cart.addProduct', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-4 py-2 rounded-lg text-sm transition-all duration-200">
                                                {{ __('messages.add_to_cart') }}
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ url()->previous() }}" class="inline-block bg-background-200 dark:bg-background-700 text-background-800 dark:text-text-50 px-6 py-2 rounded-lg hover:bg-background-300 dark:hover:bg-background-600 transition-all duration-200">
                {{ __('messages.back') }}
            </a>
        </div>
    </div>
    @endsection
    ```

### resources/views/cart/index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.shopping_cart') }}</h1>

    @if($cartItems->count() > 0)
        <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-background-50 dark:bg-background-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-text-500 dark:text-text-300 uppercase tracking-wider">{{ __('messages.product') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-text-500 dark:text-text-300 uppercase tracking-wider">{{ __('messages.price') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-text-500 dark:text-text-300 uppercase tracking-wider">{{ __('messages.quantity') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-text-500 dark:text-text-300 uppercase tracking-wider">{{ __('messages.subtotal') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-background-200 dark:divide-background-700">
                    @foreach($cartItems as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($item->product->image)
                                        <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-background-900 dark:text-text-50">{{ $item->product->name }}</div>
                                        <div class="text-sm text-text-500 dark:text-text-400">{{ Str::limit($item->product->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-background-900 dark:text-text-50">
                                ${{ number_format($item->product->price, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-20 px-2 py-1 border border-background-300 dark:border-background-600 rounded dark:bg-background-700 dark:text-text-50">
                                    <button type="submit" class="ml-2 text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                                        {{ __('messages.update') }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-background-900 dark:text-text-50">
                                ${{ number_format($item->product->price * $item->quantity, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-accent-600 dark:text-accent-400 hover:text-red-800 dark:hover:text-red-300">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="bg-background-50 dark:bg-background-700 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-xl font-bold text-background-900 dark:text-text-50">
                        {{ __('messages.total') }}: ${{ number_format($total, 2) }}
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('welcome') }}" class="bg-background-200 dark:bg-gray-600 text-background-800 dark:text-text-50 px-6 py-2 rounded-lg hover:bg-background-300 dark:hover:bg-gray-500 transition-all duration-200">
                            {{ __('messages.continue_shopping') }}
                        </a>
                        <a href="{{ route('checkout.index') }}" class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-6 py-2 rounded-lg transition-all duration-200">
                            {{ __('messages.checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-background-50 dark:bg-background-800 rounded-xl">
            <p class="text-text-600 dark:text-text-400 text-xl mb-4">{{ __('messages.cart_empty') }}</p>
            <a href="{{ route('welcome') }}" class="inline-block bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-6 py-2 rounded-lg transition-all duration-200">
                {{ __('messages.continue_shopping') }}
            </a>
        </div>
    @endif
</div>
@endsection
```

### resources/views/checkout/index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.checkout') }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Order Summary -->
        <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-background-900 dark:text-text-50 mb-4">{{ __('messages.order_details') }}</h2>
            <div class="space-y-3">
                @foreach($cartItems as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-background-700 dark:text-text-300">{{ $item->product->name }} x{{ $item->quantity }}</span>
                        <span class="font-semibold text-background-900 dark:text-text-50">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-background-200 dark:border-background-700 mt-4 pt-4">
                <div class="flex justify-between text-xl font-bold">
                    <span class="text-background-900 dark:text-text-50">{{ __('messages.total') }}</span>
                    <span class="text-primary-600 dark:text-primary-400">${{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="shipping_address" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.shipping_address') }}</label>
                    <textarea name="shipping_address" id="shipping_address" rows="4" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"></textarea>
                    @error('shipping_address')
                        <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.payment_method') }}</label>
                    <div class="space-y-2">
                        <label class="flex items-center p-3 bg-background-50 dark:bg-background-700 rounded-lg cursor-pointer hover:bg-background-100 dark:hover:bg-background-600">
                            <input type="radio" name="payment_method" value="cash_on_delivery" checked class="mr-3">
                            <span class="text-background-900 dark:text-text-50">{{ __('messages.cash_on_delivery') }}</span>
                        </label>
                        <label class="flex items-center p-3 bg-background-50 dark:bg-background-700 rounded-lg cursor-pointer hover:bg-background-100 dark:hover:bg-background-600">
                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-3">
                            <span class="text-background-900 dark:text-text-50">{{ __('messages.bank_transfer') }}</span>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 py-3 rounded-lg font-semibold transition-all duration-200">
                    {{ __('messages.place_order') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
```

### resources/views/orders/{index,show}.blade.php

-   index.blade.php

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.my_orders') }}</h1>

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-background-900 dark:text-text-50">
                                    {{ __('messages.order_number') }}: {{ $order->order_number }}
                                </h3>
                                <p class="text-sm text-text-600 dark:text-text-400">
                                    {{ __('messages.date') }}: {{ $order->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'paid') dark:bg-blue-900
                                    @elseif($order->status == 'shipped') bg-secondary-100 text-secondary-800
                                    @elseif($order->status == 'delivered') dark:text-green-300
                                    @endif">
                                    {{ __(messages.{$order->status}) }}
                                </div>
                                <p class="text-xl font-bold text-background-900 dark:text-text-50 mt-2">
                                    ${{ number_format($order->total, 2) }}
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-background-200 dark:border-background-700 pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                @foreach($order->items->take(2) as $item)
                                    <div class="flex items-center">
                                        @if($item->product->image)
                                            <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded mr-3">
                                        @endif
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-background-900 dark:text-text-50">{{ $item->product->name }}</p>
                                            <p class="text-xs text-text-600 dark:text-text-400">{{ __('messages.quantity') }}: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($order->items->count() > 2)
                                <p class="text-sm text-text-600 dark:text-text-400 mb-4">
                                    +{{ $order->items->count() - 2 }} more {{ __('messages.products') }}
                                </p>
                            @endif

                            <a href="{{ route('orders.show', $order) }}" class="inline-block bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-6 py-2 rounded-lg transition-all duration-200">
                                {{ __('messages.view_details') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-background-50 dark:bg-background-800 rounded-xl">
                <p class="text-text-600 dark:text-text-400 text-xl mb-4">{{ __('messages.no_orders_yet') }}</p>
                <a href="{{ route('welcome') }}" class="inline-block bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-6 py-2 rounded-lg transition-all duration-200">
                    {{ __('messages.continue_shopping') }}
                </a>
            </div>
        @endif
    </div>
    @endsection
    ```

-   show.blade.php

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-background-900 dark:text-text-50">
                        {{ __('messages.order_number') }}: {{ $order->order_number }}
                    </h1>
                    <p class="text-text-600 dark:text-text-400">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                    @if($order->status == 'pending') bg-yellow-100
                    @elseif($order->status == 'paid') text-blue-800
                    @elseif($order->status == 'shipped') bg-secondary-100 text-secondary-800 dark:bg-purple-900
                    @elseif($order->status == 'delivered') dark:text-green-300
                    @endif">
                    {{ __("messages.{$order->status}") }}
                </div>
            </div>

            <div class="border-t border-background-200 dark:border-background-700 pt-6 mb-6">
                <h2 class="text-lg font-semibold text-background-900 dark:text-text-50 mb-4">{{ __('messages.shipping_address') }}</h2>
                <p class="text-background-700 dark:text-text-300 whitespace-pre-line">{{ $order->shipping_address }}</p>
            </div>

            <div class="border-t border-background-200 dark:border-background-700 pt-6 mb-6">
                <h2 class="text-lg font-semibold text-background-900 dark:text-text-50 mb-4">{{ __('messages.order_details') }}</h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                @if($item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded mr-4">
                                @endif
                                <div>
                                    <p class="font-semibold text-background-900 dark:text-text-50">{{ $item->product->name }}</p>
                                    <p class="text-sm text-text-600 dark:text-text-400">{{ __('messages.quantity') }}: {{ $item->quantity }}</p>
                                    <p class="text-sm text-text-600 dark:text-text-400">${{ number_format($item->price, 2) }} {{ __('messages.each') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-background-900 dark:text-text-50">
                                    ${{ number_format($item->price * $item->quantity, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-background-200 dark:border-background-700 pt-6">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-background-900 dark:text-text-50">{{ __('messages.total') }}</span>
                    <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">${{ number_format($order->total, 2) }}</span>
                </div>
                <p class="text-sm text-text-600 dark:text-text-400 mt-2">
                    {{ __('messages.payment_method') }}: {{ __("messages.{$order->payment_method}") }}
                </p>
            </div>

            <div class="mt-6">
                <a href="{{ route('orders.index') }}" class="inline-block bg-background-200 dark:bg-background-700 text-background-800 dark:text-text-50 px-6 py-2 rounded-lg hover:bg-background-300 dark:hover:bg-background-600 transition-all duration-200">
                    {{ __('messages.back_to_orders') }}
                </a>
            </div>
        </div>
    </div>
    @endsection
    ```

### resources/views/shops/{index,show,create,edit}.blade.php

-   index.blade.php

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.all_shops') }}</h1>

        @if($shops->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($shops as $shop)
                    <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg overflow-hidden transform transition-all duration-200 hover:scale-105 hover:shadow-2xl">
                        @if($shop->logo)
                            <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-indigo-500 to-secondary-600 flex items-center justify-center">
                                <span class="text-6xl font-bold text-text-50">{{ substr($shop->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-background-900 dark:text-text-50 mb-2">{{ $shop->name }}</h3>
                            <p class="text-sm text-text-600 dark:text-text-400 mb-3 line-clamp-2">{{ $shop->description }}</p>
                            <div class="flex items-center justify-between text-sm text-text-600 dark:text-text-400 mb-4">
                                <span>{{ $shop->products_count }} {{ __('messages.products') }}</span>
                                <span>{{ __('messages.by') }} {{ $shop->user->name }}</span>
                            </div>
                            <a href="{{ route('shops.show', $shop) }}" class="block text-center bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 py-2 rounded-lg transition-all duration-200">
                                {{ __('messages.visit_shop') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $shops->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-background-50 dark:bg-background-800 rounded-xl">
                <p class="text-text-600 dark:text-text-400 text-xl">{{ __('messages.no_shops_yet') }}</p>
            </div>
        @endif
    </div>
    @endsection
    ```

-   show.blade.php

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Shop Header -->
        <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-8 mb-8">
            <div class="flex items-center">
                @if($shop->logo)
                    <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-24 h-24 rounded-lg object-cover mr-6">
                @else
                    <div class="w-24 h-24 rounded-lg bg-primary-600 flex items-center justify-center text-text-50 text-4xl font-bold mr-6">
                        {{ substr($shop->name, 0, 1) }}
                    </div>
                @endif
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-background-900 dark:text-text-50 mb-2">{{ $shop->name }}</h1>
                    <p class="text-text-600 dark:text-text-400 mb-2">{{ $shop->description }}</p>
                    <div class="flex items-center text-sm text-text-600 dark:text-text-400">
                        <span>{{ __('messages.owned_by') }} {{ $shop->user->name }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $shop->products->count() }} {{ __('messages.products') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <h2 class="text-2xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.products') }}</h2>

        @if($shop->products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($shop->products as $product)
                    <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg overflow-hidden">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-background-900 dark:text-text-50 mb-2">{{ $product->name }}</h3>
                            <p class="text-sm text-text-600 dark:text-text-400 mb-3 line-clamp-2">{{ $product->description }}</p>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xl font-bold text-primary-600 dark:text-primary-400">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-text-600 dark:text-text-400">{{ __('messages.stock') }}: {{ $product->stock }}</span>
                            </div>
                            @auth
                                <form action="{{ route('cart.addProduct', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 py-2 rounded-lg transition-all duration-200">
                                        {{ __('messages.add_to_cart') }}
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-background-50 dark:bg-background-800 rounded-xl">
                <p class="text-text-600 dark:text-text-400 text-xl">{{ __('messages.no_products_in_shop') }}</p>
            </div>
        @endif
    </div>
    @endsection
    ```

-   create.blade.php

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.create_your_shop') }}</h1>

        <form action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data" class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.shop_name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                @error('name')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.shop_description') }}</label>
                <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="logo" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.shop_logo') }} ({{ __('messages.optional') }})</label>
                <input type="file" name="logo" id="logo" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50">
                @error('logo')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <a href="{{ route('welcome') }}" class="flex-1 text-center bg-background-200 dark:bg-background-700 text-background-800 dark:text-text-50 py-2 rounded-lg hover:bg-background-300 dark:hover:bg-background-600 transition-all duration-200">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 py-2 rounded-lg transition-all duration-200">
                    {{ __('messages.create_shop') }}
                </button>
            </div>
        </form>
    </div>
    @endsection
    ```

-   edit.blade.php

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.edit_shop') }}</h1>

        <form action="{{ route('shops.update', $shop) }}" method="POST" enctype="multipart/form-data" class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.shop_name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $shop->name) }}" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                @error('name')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.shop_description') }}</label>
                <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">{{ old('description', $shop->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                @if($shop->logo)
                    <label class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.current_logo') }}</label>
                    <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-32 h-32 object-cover rounded-lg mb-2">
                @endif
                <label for="logo" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.new_logo') }} ({{ __('messages.optional') }})</label>
                <input type="file" name="logo" id="logo" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50">
                @error('logo')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <a href="{{ route('shop-owner.dashboard') }}" class="flex-1 text-center bg-background-200 dark:bg-background-700 text-background-800 dark:text-text-50 py-2 rounded-lg hover:bg-background-300 dark:hover:bg-background-600 transition-all duration-200">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 py-2 rounded-lg transition-all duration-200">
                    {{ __('messages.update') }}
                </button>
            </div>
        </form>
    </div>
    @endsection
    ```

### resources/views/shop-owner/dashboard.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Shop Header -->
    <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if($shop->logo)
                    <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="w-20 h-20 rounded-lg object-cover mr-4">
                @else
                    <div class="w-20 h-20 rounded-lg bg-primary-600 flex items-center justify-center text-text-50 text-2xl font-bold mr-4">
                        {{ substr($shop->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-background-900 dark:text-text-50">{{ $shop->name }}</h1>
                    <p class="text-text-600 dark:text-text-400">{{ __('messages.shop_owner_dashboard') }}</p>
                </div>
            </div>
            <a href="{{ route('shops.edit', $shop) }}" class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-6 py-2 rounded-lg transition-all duration-200">
                {{ __('messages.edit_shop') }}
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-text-600 dark:text-text-400 mb-1">{{ __('messages.total_products') }}</p>
                    <p class="text-3xl font-bold text-background-900 dark:text-text-50">{{ $products->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-text-600 dark:text-text-400 mb-1">{{ __('messages.pending_approvals') }}</p>
                    <p class="text-3xl font-bold text-background-900 dark:text-text-50">{{ $pendingApprovals->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-text-600 dark:text-text-400 mb-1">{{ __('messages.shop_status') }}</p>
                    <p class="text-lg font-bold {{ $shop->is_active ? 'text-green-600 dark:text-green-400' : 'text-accent-600 dark:text-accent-400' }}">
                        {{ $shop->is_active ? __('messages.active') : __('messages.inactive') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('shop-owner.products') }}" class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-primary-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-background-900 dark:text-text-50">{{ __('messages.manage_products') }}</h3>
                    <p class="text-sm text-text-600 dark:text-text-400">{{ __('messages.add_edit_products') }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('shop-owner.orders') }}" class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-secondary-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-secondary-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-background-900 dark:text-text-50">{{ __('messages.manage_orders') }}</h3>
                    <p class="text-sm text-text-600 dark:text-text-400">{{ __('messages.approve_orders') }}</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Products -->
    <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-background-900 dark:text-text-50">{{ __('messages.recent_products') }}</h2>
            <a href="{{ route('shop-owner.products.create') }}" class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 px-4 py-2 rounded-lg transition-all duration-200">
                {{ __('messages.add_product') }}
            </a>
        </div>

        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-background-50 dark:bg-background-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-500 dark:text-text-300 uppercase tracking-wider">{{ __('messages.product') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-500 dark:text-text-300 uppercase tracking-wider">{{ __('messages.price') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-500 dark:text-text-300 uppercase tracking-wider">{{ __('messages.stock') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-500 dark:text-text-300 uppercase tracking-wider">{{ __('messages.category') }}</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-background-200 dark:divide-background-700">
                        @foreach($products->take(5) as $product)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded object-cover mr-3">
                                        @endif
                                        <span class="text-sm font-medium text-background-900 dark:text-text-50">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-background-900 dark:text-text-50">${{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-background-900 dark:text-text-50">{{ $product->stock }}</td>
                                <td class="px-6 py-4 text-sm text-text-600 dark:text-text-400">{{ $product->category ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('shop-owner.products.edit', $product) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                                        {{ __('messages.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <a href="{{ route('shop-owner.products') }}" class="text-primary-600 dark:text-primary-400 hover:underline">
                    {{ __('messages.view_all_products') }} →
                </a>
            </div>
        @else
            <p class="text-center text-text-600 dark:text-text-400 py-8">{{ __('messages.no_products_yet') }}</p>
        @endif
    </div>

    <!-- Pending Approvals -->
    <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-background-900 dark:text-text-50 mb-4">{{ __('messages.pending_order_approvals') }}</h2>

        @if($pendingApprovals->count() > 0)
            <div class="space-y-4">
                @foreach($pendingApprovals as $approval)
                    <div class="border border-background-200 dark:border-background-700 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold text-background-900 dark:text-text-50">{{ __('messages.order') }} #{{ $approval->order->order_number }}</h3>
                                <p class="text-sm text-text-600 dark:text-text-400">{{ __('messages.customer') }}: {{ $approval->order->user->name }}</p>
                                <p class="text-sm text-text-600 dark:text-text-400">{{ $approval->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="text-lg font-bold text-background-900 dark:text-text-50">${{ number_format($approval->order->total, 2) }}</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('shop-owner.orders') }}" class="flex-1 text-center bg-background-100 dark:bg-background-700 text-background-800 dark:text-text-50 py-2 rounded-lg hover:bg-background-200 dark:hover:bg-background-600 transition-all duration-200">
                                {{ __('messages.view_details') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('shop-owner.orders') }}" class="text-primary-600 dark:text-primary-400 hover:underline">
                    {{ __('messages.view_all_orders') }} →
                </a>
            </div>
        @else
            <p class="text-center text-text-600 dark:text-text-400 py-8">{{ __('messages.no_pending_approvals') }}</p>
        @endif
    </div>
</div>
@endsection
```

### resources/views/shop-owner/{products,orders}/

-   products/edit.blade.php

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.edit_product') }}</h1>

        <form action="{{ route('shop-owner.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.product_name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                @error('name')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.description') }}</label>
                <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.price') }}</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                    @error('price')
                        <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.stock') }}</label>
                    <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock) }}" required class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                    @error('stock')
                        <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.category') }}</label>
                <input type="text" name="category" id="category" value="{{ old('category', $product->category) }}" class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                @error('category')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                @if($product->image)
                    <label class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.current_image') }}</label>
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-lg mb-2">
                @endif
                <label for="image" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.new_image') }} ({{ __('messages.optional') }})</label>
                <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50">
                @error('image')
                    <p class="mt-1 text-sm text-accent-600 dark:text-accent-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <a href="{{ route('shop-owner.products') }}" class="flex-1 text-center bg-background-200 dark:bg-background-700 text-background-800 dark:text-text-50 py-2 rounded-lg hover:bg-background-300 dark:hover:bg-background-600 transition-all duration-200">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-text-50 py-2 rounded-lg transition-all duration-200">
                    {{ __('messages.update') }}
                </button>
            </div>
        </form>
    </div>
    @endsection
    ```

-   orders.blade.php

    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-background-900 dark:text-text-50 mb-6">{{ __('messages.order_approvals') }}</h1>

        @if($approvals->count() > 0)
            <div class="space-y-4">
                @foreach($approvals as $approval)
                    <div class="bg-background-50 dark:bg-background-800 rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-background-900 dark:text-text-50">{{ __('messages.order') }} #{{ $approval->order->order_number }}</h3>
                                <p class="text-sm text-text-600 dark:text-text-400">{{ __('messages.customer') }}: {{ $approval->order->user->name }}</p>
                                <p class="text-sm text-text-600 dark:text-text-400">{{ __('messages.date') }}: {{ $approval->order->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $approval->is_approved ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                    {{ $approval->is_approved ? __('messages.approved') : __('messages.pending_approval') }}
                                </span>
                                <p class="text-xl font-bold text-background-900 dark:text-text-50 mt-2">${{ number_format($approval->order->total, 2) }}</p>
                            </div>
                        </div>

                        <!-- Order Items from this shop -->
                        <div class="border-t border-background-200 dark:border-background-700 pt-4 mb-4">
                            <h4 class="font-semibold text-background-900 dark:text-text-50 mb-3">{{ __('messages.products_from_your_shop') }}</h4>
                            <div class="space-y-2">
                                @foreach($approval->order->items->where('product.shop_id', $shop->id) as $item)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            @if($item->product->image)
                                                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-12 h-12 rounded object-cover mr-3">
                                            @endif
                                            <div>
                                                <p class="text-sm font-semibold text-background-900 dark:text-text-50">{{ $item->product->name }}</p>
                                                <p class="text-xs text-text-600 dark:text-text-400">{{ __('messages.quantity') }}: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                                            </div>
                                        </div>
                                        <span class="font-semibold text-background-900 dark:text-text-50">${{ number_format($item->price * $item->quantity, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if(!$approval->is_approved)
                            <!-- Approval Form -->
                            <form action="{{ route('shop-owner.orders.approve', $approval) }}" method="POST" class="border-t border-background-200 dark:border-background-700 pt-4">
                                @csrf
                                <div class="mb-4">
                                    <label for="notes-{{ $approval->id }}" class="block text-sm font-medium text-background-700 dark:text-text-300 mb-2">{{ __('messages.approval_notes') }} ({{ __('messages.optional') }})</label>
                                    <textarea name="notes" id="notes-{{ $approval->id }}" rows="2" class="w-full px-4 py-2 rounded-lg border border-background-300 dark:border-background-600 dark:bg-background-700 dark:text-text-50 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400" placeholder="{{ __('messages.add_notes_for_customer') }}"></textarea>
                                </div>
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-text-50 py-2 rounded-lg transition-all duration-200 font-semibold">
                                    {{ __('messages.approve_order') }}
                                </button>
                            </form>
                        @else
                            <div class="border-t border-background-200 dark:border-background-700 pt-4">
                                <p class="text-sm text-text-600 dark:text-text-400">
                                    {{ __('messages.approved_by') }}: {{ $approval->approver->name }}
                                    {{ __('messages.on') }} {{ $approval->approved_at->format('M d, Y h:i A') }}
                                </p>
                                @if($approval->notes)
                                    <p class="text-sm text-text-600 dark:text-text-400 mt-2">
                                        <strong>{{ __('messages.notes') }}:</strong> {{ $approval->notes }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $approvals->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-background-50 dark:bg-background-800 rounded-xl">
                <p class="text-text-600 dark:text-text-400 text-xl">{{ __('messages.no_orders_yet') }}</p>
            </div>
        @endif
    </div>
    @endsection
    ```

---

## Language & Localization

### English Translations

**File: `resources/lang/en.json`**

```json
{
    "welcome_title": "Share Your Perfect Desk Setup",
    "welcome_subtitle": "Discover amazing desk setups and get the products to build your dream workspace",
    "get_started": "Get Started",
    "featured_setups": "Featured Desk Setups",
    "share_setup": "Share Your Setup",
    "home": "Home",
    "my_posts": "My Posts",
    "cart": "Cart",
    "orders": "Orders",
    "profile": "Profile",
    "admin_panel": "Admin Panel",
    "logout": "Logout"
    "login": "Login",
    "register": "Register",
    "all_rights_reserved": "All rights reserved.",
    "views": "views",
    "products": "products",
    "view_details": "View Details",
    "add_all": "Add All to Cart",
    "no_posts_yet": "No posts yet. Be the first to share your desk setup!",
    "create_post": "Create Post",
    "create_first_post": "Create Your First Post",
    "view": "View",
    "edit": "Edit",
    "delete": "Delete",
    "confirm_delete": "Are you sure you want to delete this?",
    "title": "Title",
    "description": "Description",
    "image": "Image",
    "select_products": "Select Products Used",
    "cancel": "Cancel",
    "create": "Create",
    "update": "Update",
    "post_created": "Post created successfully!",
    "post_updated": "Post updated successfully!",
    "post_deleted": "Post deleted successfully!",
    "added_to_cart": "Product added to cart!",
    "products_added_to_cart": "All products added to cart!",
    "cart_updated": "Cart updated successfully!",
    "removed_from_cart": "Product removed from cart!",
    "cart_empty": "Your cart is empty!",
    "order_placed": "Order placed successfully!",
    "shopping_cart": "Shopping Cart",
    "product": "Product",
    "price": "Price",
    "quantity": "Quantity",
    "subtotal": "Subtotal",
    "total": "Total",
    "checkout": "Checkout",
    "continue_shopping": "Continue Shopping",
    "shipping_address": "Shipping Address",
    "payment_method": "Payment Method",
    "cash_on_delivery": "Cash on Delivery",
    "bank_transfer": "Bank Transfer",
    "place_order": "Place Order",
    "my_orders": "My Orders",
    "order_number": "Order Number",
    "date": "Date",
    "status": "Status",
    "pending": "Pending",
    "paid": "Paid",
    "shipped": "Shipped",
    "delivered": "Delivered",
    "order_details": "Order Details",
    "posted_by": "Posted by"
}
```

### Indonesian Translations

**File: `resources/lang/id.json`**

```json
{
    "welcome_title": "Bagikan Setup Meja Sempurna Anda",

    "welcome_subtitle": "Temukan setup meja yang menakjubkan dan dapatkan produk untuk membangun workspace impian Anda",

    "get_started": "Mulai Sekarang",

    "featured_setups": "Setup Meja Pilihan",

    "share_setup": "Bagikan Setup Anda",

    "home": "Beranda",

    "my_posts": "Postingan Saya",

    "cart": "Keranjang",

    "orders": "Pesanan",

    "profile": "Profil",

    "admin_panel": "Panel Admin",

    "logout": "Keluar",

    "login": "Masuk",

    "register": "Daftar",

    "all_rights_reserved": "Hak cipta dilindungi.",

    "views": "tayangan",

    "products": "produk",

    "view_details": "Lihat Detail",

    "add_all": "Tambah Semua ke Keranjang",

    "no_posts_yet": "Belum ada postingan. Jadilah yang pertama membagikan setup meja Anda!",

    "create_post": "Buat Postingan",

    "create_first_post": "Buat Postingan Pertama",

    "view": "Lihat",

    "edit": "Edit",

    "delete": "Hapus",

    "confirm_delete": "Apakah Anda yakin ingin menghapus ini?",

    "title": "Judul",

    "description": "Deskripsi",

    "image": "Gambar",

    "select_products": "Pilih Produk yang Digunakan",

    "cancel": "Batal",

    "create": "Buat",

    "update": "Perbarui",

    "post_created": "Postingan berhasil dibuat!",

    "post_updated": "Postingan berhasil diperbarui!",

    "post_deleted": "Postingan berhasil dihapus!",

    "added_to_cart": "Produk ditambahkan ke keranjang!",

    "products_added_to_cart": "Semua produk ditambahkan ke keranjang!",

    "cart_updated": "Keranjang berhasil diperbarui!",

    "removed_from_cart": "Produk dihapus dari keranjang!",

    "cart_empty": "Keranjang Anda kosong!",

    "order_placed": "Pesanan berhasil dibuat!",

    "shopping_cart": "Keranjang Belanja",

    "product": "Produk",

    "price": "Harga",

    "quantity": "Jumlah",

    "subtotal": "Subtotal",

    "total": "Total",

    "checkout": "Checkout",

    "continue_shopping": "Lanjut Belanja",

    "shipping_address": "Alamat Pengiriman",

    "payment_method": "Metode Pembayaran",

    "cash_on_delivery": "Bayar di Tempat",

    "bank_transfer": "Transfer Bank",

    "place_order": "Buat Pesanan",

    "my_orders": "Pesanan Saya",

    "order_number": "Nomor Pesanan",

    "date": "Tanggal",

    "status": "Status",

    "pending": "Menunggu",

    "paid": "Dibayar",

    "shipped": "Dikirim",

    "delivered": "Diterima",

    "order_details": "Detail Pesanan",

    "posted_by": "Diposting oleh"
}
```

---

## Middleware

### SetLocale Middleware

**File: `app/Http/Middleware/SetLocale.php`**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}
```

Register in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\SetLocale::class,
    ]);
})
```

---

## Admin Panel (Backpack CRUD)

### Database Seeder

**File: `database/seeders/DatabaseSeeder.php`**

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@deskbyhorizon.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Shop owner
        User::create([
            'name' => 'Shop Owner',
            'email' => 'owner@deskbyhorizon.com',
            'password' => Hash::make('password'),
            'role' => 'shop_owner',
        ]);

        // Regular user
        User::create([
            'name' => 'John Doe',
            'email' => 'user@deskbyhorizon.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Sample products
        $products = [
            ['name' => 'Mechanical Keyboard', 'price' => 129.99, 'stock' => 50, 'category' => 'Keyboards'],
            ['name' => 'Wireless Mouse', 'price' => 79.99, 'stock' => 75, 'category' => 'Mice'],
            ['name' => '27" Monitor', 'price' => 399.99, 'stock' => 30, 'category' => 'Monitors'],
            ['name' => 'LED Desk Lamp', 'price' => 49.99, 'stock' => 100, 'category' => 'Lighting'],
            ['name' => 'Standing Desk', 'price' => 599.99, 'stock' => 20, 'category' => 'Furniture'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
```

Run seed:

```bash
php artisan db:seed
```

---

## Testing & Deployment

### Configuration Files

**tailwind.config.js**:

```javascript
export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    darkMode: "class",
    theme: { extend: {} },
    plugins: [],
};
```

**vite.config.js**: Use default Laravel configuration

### Complete Installation

```bash
# Install composer dependencies
composer install

# Install npm dependencies
npm install

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Start development server
php artisan serve
```

### Access Points

-   Frontend: `http://localhost:8000`
-   Login: `http://localhost:8000/auth`
-   Admin Panel: `http://localhost:8000/admin`

### Test Accounts

**Admin**: admin@deskbyhorizon.com / password
**Shop Owner**: owner@deskbyhorizon.com / password
**User**: user@deskbyhorizon.com / password

---

## Troubleshooting

### Common Issues

| Issue                         | Solution                                                           |
| ----------------------------- | ------------------------------------------------------------------ |
| Storage folder not accessible | `chmod -R 775 storage bootstrap/cache && php artisan storage:link` |
| Migration errors              | `php artisan migrate:fresh --seed`                                 |
| Assets not loading            | `npm run build && php artisan optimize:clear`                      |
| Dark mode not working         | Ensure Alpine.js is installed: `npm install alpinejs`              |
| Routes not working            | `php artisan route:clear`                                          |
| CSRF token errors             | Check `<meta name="csrf-token">` in layout                         |

---

## Project Structure

```
desk-by-horizon/
├── app/
│   ├── Http/
│   │   ├── Controllers/ (10+ controllers)
│   │   ├── Middleware/ (2+ middleware)
│   │   └── Policies/ (5+ policies)
│   └── Models/ (8 models)
├── database/
│   ├── migrations/ (10+ migrations)
│   └── seeders/
├── resources/
│   ├── css/ (Tailwind)
│   ├── js/ (Alpine.js)
│   ├── lang/ (en.json, id.json)
│   └── views/ (30+ blade templates)
├── routes/
│   └── web.php (30+ routes)
├── storage/
│   ├── app/public/
│   │   ├── posts/
│   │   ├── products/
│   │   └── shops/
├── .env (environment variables)
├── composer.json
├── package.json
└── tailwind.config.js
```

---

## Key Features Summary

✅ Multi-vendor e-commerce platform
✅ User role management (Admin, Shop Owner, Regular User)
✅ Desk setup sharing with product linking
✅ Shopping cart & checkout system
✅ Order management with multi-shop approval workflow
✅ Admin panel for system management
✅ Dark mode with system preference detection
✅ Responsive design for all devices
✅ Multi-language support (English & Indonesian)
✅ Image upload & storage management
✅ Security with Laravel Breeze & Policies

---

**Ready to build your desk setup marketplace! 🚀**
