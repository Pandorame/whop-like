<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCampaign extends Model
{
    protected $fillable = ['content_url', 'platform', 'views', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

   // app/Models/UserCampaign.php

protected static function boot()
{
    parent::boot();

    static::saved(function ($userCampaign) {
        // Recalculate campaign's paid amount whenever a submission changes
        $userCampaign->campaign->refresh()->updatePaidAmount();
    });

    static::deleted(function ($userCampaign) {
        // Recalculate if a submission is deleted
        $userCampaign->campaign->refresh()->updatePaidAmount();
    });
}

// Add this new method to handle status changes
public function markAsPaid()
{
    $this->update([
        'status' => 'paid',
        'paid_at' => now(),
    ]);
    
    $this->campaign->refresh()->updatePaidAmount();
}

}
