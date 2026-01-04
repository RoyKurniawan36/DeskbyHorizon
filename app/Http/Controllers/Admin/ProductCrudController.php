<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
    }

    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('shop_id')->type('select')->label('Shop')->attribute('name');
        CRUD::column('price')->type('number')->prefix('$')->decimals(2);
        CRUD::column('stock')->type('number');
        CRUD::column('category');
        CRUD::column('created_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'shop_id' => 'nullable|exists:shops,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable',
            'category' => 'nullable|max:255',
        ]);

        CRUD::field('name');
        CRUD::field('shop_id')->type('relationship')->label('Shop')->attribute('name')->model('App\Models\Shop')->hint('Leave empty for global products');
        CRUD::field('description')->type('textarea');
        CRUD::field('price')->type('number')->prefix('$')->attributes(['step' => '0.01', 'min' => '0']);
        CRUD::field('stock')->type('number')->attributes(['min' => '0']);
        CRUD::field('category');
        CRUD::field('image')->type('upload')->upload(true)->disk('public');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}