<?php

namespace App\Livewire;

use App\Models\Admin;
use App\Models\Agent as AgentModel;
use App\Models\Deposit as DepositModel;
use App\Models\Payment;
use App\Models\PaymentAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Deposit extends Component
{
    use WithFileUploads;

    public $selectedAgent = '';
    public $selectedPaymentAccount = '';
    public $selectedPayment = '';
    public $amount = '';
    public $reference = '';
    public $agents = [];
    public $payments = [];
    public $paymentAccounts = [];
    
    public $slip;

    protected $rules = [
        'selectedAgent' => 'required|exists:admins,id',
        'selectedPayment' => 'required|exists:payments,id',
        'selectedPaymentAccount' => 'required|exists:payment_accounts,id',
        'amount' => 'required|numeric|min:0.01',
        'slip' => 'required|image|max:2048',
    ];

    public function removeSlip()
    {
        $this->slip = null;
    }

    public function mount()
    {
        $this->agents = Admin::where('level','AGENT')->get();
    }

    public function updatedSelectedAgent()
    {
        if ($this->selectedAgent) {
            $this->payments = Payment::get();
            $this->selectedPayment = '';
            $this->paymentAccounts = [];
            $this->selectedPaymentAccount = '';
        } else {
            $this->payments = [];
            $this->selectedPayment = '';
            $this->paymentAccounts = [];
            $this->selectedPaymentAccount = '';
        }
    }

    public function updatedSelectedPayment()
    {
        if ($this->selectedPayment) {
            $this->paymentAccounts = PaymentAccount::where('payment_id', $this->selectedPayment)->where('admin_id',$this->selectedAgent)->get();
            $this->selectedPaymentAccount = '';
        } else {
            $this->paymentAccounts = [];
            $this->selectedPaymentAccount = '';
        }
    }

    public function makeDeposit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $slipPath = null;
            if ($this->slip) {
                $slipPath = $this->slip->store('slips', 'public');
            }

            // Create deposit record
            DepositModel::create([
                'to_admin_id' => $this->selectedAgent,
                'user_id' => Auth::id(),
                'payment_id' => $this->selectedPayment,
                'payment_account_id' => $this->selectedPaymentAccount,
                'amount' => $this->amount,
                'slip' => $slipPath,
                'status' => 'completed',
            ]);

            DB::commit();

            session()->flash('message', 'Deposit successfully processed!');
            $this->reset(['selectedAgent', 'selectedPaymentAccount', 'amount', 'slip']);
            $this->agents = Admin::where('level','AGENT')->get();
            
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            session()->flash('error', 'Error processing deposit: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.deposit');
    }
}