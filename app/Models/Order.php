<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use CrudTrait;
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