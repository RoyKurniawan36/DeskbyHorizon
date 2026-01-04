<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');
    }

    protected function setupListOperation()
    {
        CRUD::column('title');
        CRUD::column('user_id')->type('select')->label('Author')->attribute('name');
        CRUD::column('views')->type('number');
        CRUD::column('created_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'title' => 'required|min:2|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'required',
            'image' => 'required|image|max:2048',
        ]);

        CRUD::field('title');
        CRUD::field('user_id')->type('relationship')->label('Author')->attribute('name')->model('App\Models\User');
        CRUD::field('description')->type('textarea');
        CRUD::field('image')->type('upload')->upload(true)->disk('public');
        CRUD::field('views')->type('number')->default(0)->attributes(['readonly' => 'readonly']);
        CRUD::field('products')->type('relationship')->label('Products')->pivot(true);
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation([
            'title' => 'required|min:2|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        CRUD::field('title');
        CRUD::field('user_id')->type('relationship')->label('Author')->attribute('name')->model('App\Models\User');
        CRUD::field('description')->type('textarea');
        CRUD::field('image')->type('upload')->upload(true)->disk('public');
        CRUD::field('views')->type('number')->attributes(['readonly' => 'readonly']);
        CRUD::field('products')->type('relationship')->label('Products')->pivot(true);
    }
}