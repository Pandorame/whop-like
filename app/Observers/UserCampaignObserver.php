<?php

namespace App\Observers;

use App\Models\UserCampaign;

class UserCampaignObserver
{
    /**
     * Handle the UserCampaign "created" event.
     */
    public function created(UserCampaign $userCampaign): void
    {
        //
    }

    /**
     * Handle the UserCampaign "updated" event.
     */
    public function updated(UserCampaign $userCampaign)
    {
        if ($userCampaign->isDirty('status') && $userCampaign->status === 'paid') {
            $userCampaign->campaign->updatePaidAmount();
        }
    }
    /**
     * Handle the UserCampaign "deleted" event.
     */
    public function deleted(UserCampaign $userCampaign): void
    {
        //
    }

    /**
     * Handle the UserCampaign "restored" event.
     */
    public function restored(UserCampaign $userCampaign): void
    {
        //
    }

    /**
     * Handle the UserCampaign "force deleted" event.
     */
    public function forceDeleted(UserCampaign $userCampaign): void
    {
        //
    }
}
