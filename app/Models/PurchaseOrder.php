<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected  $table = 'purchase_orders';

    protected $fillable = [
        'user_id',
        'subtotal',
        'discount',
        'customer_id',
        'address',
        'phone',
        'email',
        'total',
        'code',
        'status',
        'delivery_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function purchaseOrderDetail()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }


}
