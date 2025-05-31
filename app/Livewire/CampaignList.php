<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Campaign;

class CampaignList extends Component
{
    public function render()
    {
        return view('livewire.campaign-list', [
            'campaigns' => Campaign::where('is_active', true)->get()
        ]);
    }
}