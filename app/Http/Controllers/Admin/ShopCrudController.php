<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ShopCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Shop::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/shop');
        CRUD::setEntityNameStrings('shop', 'shops');
    }

    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('user_id')->type('select')->label('Owner');
        CRUD::column('slug');
        CRUD::column('is_active')->type('boolean');
        CRUD::column('created_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'user_id' => 'required|exists:users,id',
            'slug' => 'nullable|unique:shops,slug',
            'description' => 'nullable',
        ]);

        CRUD::field('name');
        CRUD::field('user_id')->type('select')->label('Owner')->attribute('name')->model('App\Models\User');
        CRUD::field('description')->type('textarea');
        CRUD::field('slug')->hint('Leave empty to auto-generate from name');
        CRUD::field('logo')->type('upload')->upload(true)->disk('public');
        CRUD::field('is_active')->type('boolean')->default(1);
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'user_id' => 'required|exists:users,id',
            'slug' => 'nullable|unique:shops,slug,' . CRUD::getCurrentEntryId(),
            'description' => 'nullable',
        ]);

        $this->setupCreateOperation();
    }
}