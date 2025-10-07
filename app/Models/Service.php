<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'subcategory_id',
        'title',
        'description',
        'price',
        'job_type',
        'experience',
        'industry',
        'contact',
        'address',
        'service_type',
        'discount_price',
        'images',
        'latitude',
        'longitude',
        'slug',
        'highlight_until',
        'is_highlight',
        'highlight_fee',
    ];


    protected static function booted()
    {
        static::creating(function ($service) {
            $service->slug = Str::slug($service->title);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $casts = [
        'images' => 'array',
        'highlight_until' => 'datetime',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relasi ke subcategory
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
