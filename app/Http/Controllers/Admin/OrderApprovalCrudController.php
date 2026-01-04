<?php
namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class OrderApprovalCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\OrderApproval::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order-approval');
        CRUD::setEntityNameStrings('order approval', 'order approvals');
    }

    protected function setupListOperation()
    {
        // PERFORMANCE: Eager load relationships
        $this->crud->with(['order', 'shop', 'approver']);

        // UX: Link to the order for easier navigation
        CRUD::column('order_id')
            ->type('relationship')
            ->label('Order')
            ->attribute('order_number')
            ->wrapper([
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('order/'.$related_key.'/show');
                },
            ]);

        CRUD::column('shop_id')->type('relationship')->label('Shop')->attribute('name');
        
        // UX: Use a Checkmark/X icon for Boolean
        CRUD::column('is_approved')
            ->type('boolean')
            ->label('Approved')
            ->options([0 => 'Pending', 1 => 'Approved']);

        CRUD::column('approved_at')->type('datetime')->label('Timestamp');
        CRUD::column('approver_id')->type('relationship')->label('Approver')->attribute('name');
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation([
            'is_approved' => 'required|boolean',
            'notes' => 'nullable|max:500',
        ]);

        // Layout: Use a grid to group information
        CRUD::field('order_id')->type('relationship')->label('Order')->attribute('order_number')->attributes(['disabled' => 'disabled'])->wrapper(['class' => 'form-group col-md-6']);
        CRUD::field('shop_id')->type('relationship')->label('Shop')->attribute('name')->attributes(['disabled' => 'disabled'])->wrapper(['class' => 'form-group col-md-6']);
        
        CRUD::field('is_approved')
            ->type('radio') // Radio is often better than a toggle for explicit "Approve/Reject"
            ->options([
                0 => "Pending / Rejected",
                1 => "Approved"
            ])
            ->inline(true);

        CRUD::field('notes')->type('textarea')->label('Approval Notes');

        // Logic: Hidden fields to handle timestamps and user IDs automatically
        // We handle the actual value assignment in the model or a controller override
    }

    /**
     * Override the update function to automatically set the approver and timestamp
     */
    protected function setupUpdateOperationCustom()
    {
        $this->setupUpdateOperation();
        
        // When saving, automatically inject the current admin's ID and current time
        \App\Models\OrderApproval::saving(function($entry) {
            if ($entry->is_approved && is_null($entry->approved_at)) {
                $entry->approved_at = now();
                $entry->approver_id = backpack_user()->id;
            }
        });
    }
}