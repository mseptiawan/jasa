<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use SoftDeletes;
    // Kolom yang boleh diisi mass assignment
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
        'images',
        'latitude',
        'longitude',
        'slug', // jangan lupa tambahkan slug
        'is_highlight',      // untuk status highlight
        'highlight_until',   // tanggal habis highlight
        'highlight_fee',     // fee yang dibayarkan
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

    // Jika pakai JSON di images/tags, otomatis cast ke array
    protected $casts = [
        'images' => 'array',
        'highlight_until' => 'datetime', // ini penting

    ];

    // Relasi ke user
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
