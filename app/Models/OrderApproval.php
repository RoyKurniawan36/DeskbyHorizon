<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderApproval extends Model
{
    use CrudTrait;
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