<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'address',
        'skills',
        'experience',
        'portfolio',
        'education',
        'id_card',
        'selfie',
        'cv',
        'status',
        'admin_notes'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
