<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $casts = [
        'platforms' => 'array',
    ];

    public function advertiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advertiser_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(UserCampaign::class);
    }

    public function getProgressAttribute(): float
    {
        return $this->budget > 0 ? ($this->amount_paid / $this->budget) * 100 : 0;
    }
}
