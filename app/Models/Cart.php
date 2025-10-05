<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'service_id', 'quantity'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'slug'); // kalau service_id sekarang slug
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
