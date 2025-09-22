<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
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
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke subcategory
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
