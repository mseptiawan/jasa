<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'customer_id',
        'seller_id',
        'price',
        'platform_fee',
        'total_price',
        'status',
        'payment_method',
        'customer_address', // tambahkan ini
        'customer_phone',   // tambahkan ini
        'note',             // tambahkan ini
    ];

    /**
     * Relasi ke Service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relasi ke customer (user yang beli)
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Relasi ke seller (owner jasa)
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
