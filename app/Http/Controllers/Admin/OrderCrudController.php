<?php
namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class OrderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order');
        CRUD::setEntityNameStrings('order', 'orders');
    }
    public function approve($id)
    {
        // 1. Get the entry
        $entry = $this->crud->getEntry($id);
    
        // 2. Perform validation (Check status and custom model logic)
        if ($entry->status !== 'pending') {
            \Alert::error('Order is already processed.')->flash();
            return redirect()->back();
        }
    
        if (!$entry->isFullyApproved()) {
            \Alert::warning('Order cannot be approved yet. Waiting for shop approvals.')->flash();
            return redirect()->back();
        }
    
        // 3. Update status
        $entry->status = 'approved';
        $entry->save();
    
        // 4. Feedback & Redirect
        \Alert::success('Order #'.$entry->order_number.' has been approved successfully.')->flash();
    
        return redirect()->back();
    }

    protected function setupListOperation()
    {
        // 1. PERFORMANCE: Eager load relationships to prevent N+1 queries
        $this->crud->with(['user', 'items', 'approvals']);

        CRUD::column('order_number')->label('#');
        
        // Use 'relationship' instead of 'select' for better performance/linking
        CRUD::column('user_id')->type('relationship')->label('Customer')->attribute('name');

        // 2. UI: Use badges for status to make it scannable
        CRUD::column('status')->type('select_from_array')->options($this->getStatusOptions())->wrapper([
            'element' => 'span',
            'class' => function ($crud, $column, $entry, $related_key) {
                return 'badge ' . match($entry->status) {
                    'pending' => 'badge-warning',
                    'approved', 'paid' => 'badge-info',
                    'shipped' => 'badge-primary',
                    'delivered' => 'badge-success',
                    default => 'badge-secondary'
                };
            },
        ]);

        CRUD::column('total')->type('number')->prefix('$')->decimals(2);
        CRUD::column('payment_method')->type('select_from_array')->options($this->getPaymentOptions());
        CRUD::column('created_at')->type('datetime');

        // 3. BUTTON: Add your custom approval button
        $this->crud->addButtonFromView('line', 'approve_order', 'approve_order', 'beginning');
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation([
            'status' => 'required|in:' . implode(',', array_keys($this->getStatusOptions())),
            'shipping_address' => 'required',
        ]);

        CRUD::field('order_number')->attributes(['readonly' => 'readonly']);
        CRUD::field('user_id')->type('relationship')->label('Customer')->attribute('name')->attributes(['disabled' => 'disabled']);
        
        CRUD::field('status')->type('select_from_array')->options($this->getStatusOptions());
        CRUD::field('total')->type('number')->prefix('$')->attributes(['readonly' => 'readonly']);
        CRUD::field('shipping_address')->type('textarea');
        
        CRUD::field('payment_method')->type('select_from_array')
            ->options($this->getPaymentOptions())
            ->attributes(['disabled' => 'disabled']);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        CRUD::column('shipping_address')->type('textarea');
        
        // Show item count or summarized list
        CRUD::column('items')->type('relationship')->label('Order Items')->attribute('product_name');
        CRUD::column('approvals')->type('relationship')->label('Shop Approvals');
    }

    // Helper methods to keep code DRY
    private function getStatusOptions() {
        return [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'paid' => 'Paid',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered'
        ];
    }

    private function getPaymentOptions() {
        return [
            'cash_on_delivery' => 'Cash on Delivery',
            'bank_transfer' => 'Bank Transfer'
        ];
    }
}