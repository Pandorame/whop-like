<?php

namespace App\Livewire;

use App\Helpers\Enums\TransitionType;
use App\Helpers\Enums\WithdrawStatus;
use App\Models\Admin;
use App\Models\Payment;
use App\Models\Transation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Withdraw extends Component
{
    public $amount = '';
    public $paymentMethod = '';
    public $accountNumber = '';
    public $accountName = '';
    public $availableBalance = 0;
    public $commissionRate = 12; // 12% commission

    #[Locked]
    public $amountAfterCommission = 0;
    public $commissionAmount = 0;
    public $agents = [];
    public $agentId= '';
    
    protected $rules = [
        'amount' => 'required|numeric|min:1000',
        'paymentMethod' => 'required|exists:payments,id',
        'accountNumber' => 'required|min:6',
        'accountName' => 'required|min:3',
        'agentId' =>'required|exists:admins,id',
    ];
    
    protected $messages = [
        'amount.required' => 'Please enter an amount to withdraw',
        'amount.numeric' => 'Amount must be a number',
        'amount.min' => 'Minimum withdrawal amount is 1,000 Kyats',
        'paymentMethod.required' => 'Please select a payment method',
        'accountNumber.required' => 'Please enter your account number',
        'accountNumber.min' => 'Account number must be at least 6 characters',
        'accountName.required' => 'Please enter the account holder name',
        'accountName.min' => 'Account name must be at least 3 characters',
    ];
    
    public function mount()
    {
        $user = Auth::user();
        $this->availableBalance = $user->wallet->amount ?? 0;
        $this->agents = Admin::where('level','AGENT')->get();
    }
    
    public function updatedAmount()
    {
        if (is_numeric($this->amount)) {
            $this->commissionAmount = $this->amount * ($this->commissionRate / 100);
            $this->amountAfterCommission = $this->amount - $this->commissionAmount;
        } else {
            $this->commissionAmount = 0;
            $this->amountAfterCommission = 0;
        }
    }

    
    public function withdraw()
    {
        $this->validate();
        
        $user = User::find(Auth::id());
        
        // Check if user has enough balance
        if ($user->wallet->amount < $this->amount) {
            $this->addError('amount', 'Insufficient balance in your wallet');
            return;
        }
        
        DB::beginTransaction();
        // Process withdrawal
        try {
            // Deduct from user's wallet
            $user->wallet->decrement('amount', $this->amount);
            
            // Create transaction record
            DB::table('withdraws')->insert([
                'to_admin_id' => $this->agentId,
                'user_id' => $user->id,
                'amount' => $this->amount,
                'status' => WithdrawStatus::Processing,
                'payment_id' => $this->paymentMethod,
                'account_number' => $this->accountNumber,
                'account_name' => $this->accountName,
                'amount' => $this->amount,
                'transaction_fees' => $this->commissionAmount,
                'real_amount' => $this->amountAfterCommission,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            $transition = new Transation();
            $transition->transationable_id = $user->id;
            $transition->transationable_type = get_class($user);
            $transition->amount = $this->amount;
            $transition->type = TransitionType::CashOut;
            $transition->save();
            
            session()->flash('message', 'Withdrawal request submitted successfully! You will receive ' . number_format($this->amountAfterCommission) . ' Kyats after commission.');
            
            DB::commit();
            // Reset form
            $this->reset(['amount', 'paymentMethod', 'accountNumber', 'accountName']);
            $this->commissionAmount = 0;
            $this->amountAfterCommission = 0;
            $this->availableBalance = $user->wallet_balance;
            
        } catch (\Exception $e) {

            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.withdraw', [
            'payments' => Payment::get()
        ]);
    }
}
