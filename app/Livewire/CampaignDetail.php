<?php
// app/Http/Livewire/CampaignDetail.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Campaign;
use Livewire\WithFileUploads;

class CampaignDetail extends Component
{
    use WithFileUploads;

    public Campaign $campaign;
    public $contentUrl;
    public $platform;
    public $agreeTerms = false;

    protected $rules = [
        'contentUrl' => 'required|url',
        'platform' => 'required|in:twitter,instagram,tiktok,youtube',
        'agreeTerms' => 'accepted',
    ];

    public function submit()
    {
        $this->validate();

        $this->campaign->submissions()->create([
            'user_id' => auth()->id(),
            'content_url' => $this->contentUrl,
            'platform' => $this->platform,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Your submission has been received!');
        return redirect()->route('campaigns.index');
    }

    public function render()
    {
        return view('livewire.campaign-detail');
    }
}