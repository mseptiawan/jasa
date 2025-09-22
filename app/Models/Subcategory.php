<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'category_id', 'description'];


    // Relasi ke service
    public function services()
    {
        return $this->hasMany(Service::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
