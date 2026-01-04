<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('email');
        CRUD::column('role')->type('select_from_array')->options([
            'user' => 'User',
            'shop_owner' => 'Shop Owner',
            'admin' => 'Admin'
        ]);
        CRUD::column('created_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:user,shop_owner,admin',
        ]);

        CRUD::field('name');
        CRUD::field('email')->type('email');
        CRUD::field('password')->type('password');
        CRUD::field('role')->type('select_from_array')->options([
            'user' => 'User',
            'shop_owner' => 'Shop Owner',
            'admin' => 'Admin'
        ])->default('user');
        CRUD::field('avatar')->type('upload')->upload(true)->disk('public');
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . CRUD::getCurrentEntryId(),
            'password' => 'nullable|min:8',
            'role' => 'required|in:user,shop_owner,admin',
        ]);

        CRUD::field('name');
        CRUD::field('email')->type('email');
        CRUD::field('password')->type('password')->hint('Leave empty to keep current password');
        CRUD::field('role')->type('select_from_array')->options([
            'user' => 'User',
            'shop_owner' => 'Shop Owner',
            'admin' => 'Admin'
        ]);
        CRUD::field('avatar')->type('upload')->upload(true)->disk('public');
    }
}