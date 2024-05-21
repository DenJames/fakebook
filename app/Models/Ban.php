<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'banned_by_id',
        'reason',
        'expires_at',
    ];

    protected $dates = [
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by_id');
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function scopeActive($query)
    {
        return $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopePermanent($query)
    {
        return $query->whereNull('expires_at');
    }

    public function scopeTemporary($query)
    {
        return $query->whereNotNull('expires_at');
    }

    public function formattedExpiresAt()
    {
        return $this->expires_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->expires_at)->format('D, d M Y H:i:s') : 'Never';
    }
}
