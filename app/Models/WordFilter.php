<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordFilter extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'bannable' => 'boolean',
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeBannable($query)
    {
        return $query->where('bannable', true);
    }

    public function scopeNotBannable($query)
    {
        return $query->where('bannable', false);
    }
}
