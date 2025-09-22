<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // Auto-generate slug saat membuat atau update category
    protected static function booted()
    {
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    // Relasi ke Subcategory
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
