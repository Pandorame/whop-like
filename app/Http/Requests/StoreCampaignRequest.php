<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
{
    return [
        'payout_structure_type' => 'required|in:per_view,percentage,fixed',
        'payout_amount' => 'required|numeric|min:0.01',
        'payout_threshold' => 'required_if:payout_structure_type,per_view|integer|min:1',
        // ... other rules ...
    ];
}

}
