<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{

    protected $fillable = [
        // ... other fields ...
        'payout_structure_type', 
        'payout_amount',
        'payout_threshold'
    ];
    
    public function calculateEarnings($views)
    {
        switch ($this->payout_structure_type) {
            case 'per_view':
                return $views * $this->payout_amount;
            case 'percentage':
                return ($this->payout_amount / 100) * $this->budget;
            case 'fixed':
                return $this->payout_amount;
            default:
                return 0;
        }
    }
    
    public function getPayoutStructureDisplayAttribute()
    {
        switch ($this->payout_structure_type) {
            case 'per_view':
                return "{$this->payout_threshold} views / \${$this->payout_amount}";
            case 'percentage':
                return "{$this->payout_amount}% of budget";
            case 'fixed':
                return "Fixed \${$this->payout_amount}";
            default:
                return "Custom payout";
        }
    }

    
    
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

    public function updatePaidAmount()
{
    $this->amount_paid = $this->submissions()
        ->where('status', 'paid')
        ->sum('earnings');
        
    $this->save();
}

public function getProgressAttribute()
{
    if ($this->budget <= 0) return 0;
    
    $progress = ($this->amount_paid / $this->budget) * 100;
    return min(100, max(0, $progress)); // Ensure between 0-100
}

}
