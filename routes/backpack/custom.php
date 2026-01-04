<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    // --- User & Shop Management ---
    Route::crud('user', 'UserCrudController');
    Route::crud('shop', 'ShopCrudController');

    // --- Catalog Management ---
    Route::crud('product', 'ProductCrudController');
    Route::crud('post', 'PostCrudController');

    // --- Order Management ---
    /** * IMPORTANT: Custom routes MUST be defined BEFORE the CRUD::resource 
     * to prevent the "approve" string being interpreted as an ID.
     */
    Route::get('order/{id}/approve', 'OrderCrudController@approve');
    Route::crud('order', 'OrderCrudController');

    // --- Approval Workflow ---
    Route::crud('order-approval', 'OrderApprovalCrudController');
    
}); // this should be the last line