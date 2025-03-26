<?php

namespace App\Livewire;

use App\Helpers\Enums\TransitionType;
use App\Models\Admin;
use App\Models\Payment;
use App\Models\Transation;
use App\Models\Withdraw;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminWallet extends Component implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    public ?array $data = [];

    public Admin $admin;

    public function mount()
    {
        $this->form->fill();
        $this->admin = Admin::where('id', Auth::id())->first();
    }

    public function exchangeAction(): Action
    {
        return Action::make('exchange')
            ->icon('heroicon-m-arrows-right-left')->iconButton()
            ->form([
                TextInput::make('amount')->numeric()->required()->rules('max:' . $this->admin->receive_wallet),
            ])
            ->action(function (array $arguments, $data) {

                $amount = $data['amount'];
                if ($amount > $this->admin->receive_wallet || $amount < 10) {
                    Notification::make()->title('Insufficient funds')->danger()->send();
                    return;
                }

                $commessonFees = 5 / 100 * $amount;
                $this->admin->increment('wallet', $amount + $commessonFees);
                $this->admin->decrement('receive_wallet', $amount);

                $transition = new Transation();
                $transition->transationable_id = $this->admin->id;
                $transition->transationable_type = get_class($this->admin);
                $transition->amount = $amount + $commessonFees;
                $transition->type = TransitionType::CashIn;
                $transition->save();

                Notification::make()->title('Exchange Successfully!')->success()->send();

            });
    }

    public function withdrawAction(): Action
    {
        return Action::make('withdraw')
            ->icon('heroicon-m-arrow-trending-up')->iconButton()
            ->form([
                TextInput::make('amount')->numeric()->required()->rules('max:' . $this->admin->receive_wallet),
                Select::make('payment_id')
                    ->options(fn() => Payment::pluck('name','id'))
                    ->required(),
                TextInput::make('account_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('account_number')
                    ->required()
                    ->maxLength(255),

            ])
            ->action(function (array $arguments, $data) {

                $amount = $data['amount'];
                if ($amount > $this->admin->receive_wallet || $amount < 10) {
                    Notification::make()->title('Insufficient funds')->danger()->send();
                    return;
                }

                $withdraw = new Withdraw();
                $withdraw->amount = $amount;
                $withdraw->real_amount = $amount;
                $withdraw->transaction_fees = 0;
                $withdraw->payment_id = $data['payment_id'];
                $withdraw->account_name = $data['account_name'];
                $withdraw->account_number = $data['account_number'];
                $withdraw->to_admin_id = $this->admin->parent_id;
                $withdraw->admin_id = $this->admin->id;
                $withdraw->save();

                $this->admin->decrement('receive_wallet', $amount);

                $transition = new Transation();
                $transition->transationable_id = $this->admin->id;
                $transition->transationable_type = get_class($this->admin);
                $transition->amount = $amount;
                $transition->type = TransitionType::CashOut;
                $transition->save();

                Notification::make()->title('Exchange Successfully!')->success()->send();

            });
    }

    // New transfer action for DTR to agent
    public function transferAction(): Action
    {
        return Action::make('transfer')
            ->icon('heroicon-m-paper-airplane')->iconButton()
            ->form([
                Select::make('agent_id')
                    ->label('Select Agent')
                    ->options(fn() => Admin::where('level', 'AGENT')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('amount')
                    ->label('Amount to Transfer')
                    ->numeric()
                    ->required()
                    ->rules('max:' . $this->admin->wallet),
            ])
            ->action(function (array $arguments, $data) {
                $amount = $data['amount'];
                $agentId = $data['agent_id'];
                
                // Validate amount
                if ($amount > $this->admin->wallet || $amount < 10) {
                    Notification::make()->title('Insufficient funds or minimum transfer amount not met')->danger()->send();
                    return;
                }
                
                // Get the agent
                $agent = Admin::find($agentId);
                if (!$agent) {
                    Notification::make()->title('Agent not found')->danger()->send();
                    return;
                }
                
                // Begin transaction
                DB::beginTransaction();
                
                try {
                    // Deduct from admin wallet
                    $this->admin->decrement('wallet', $amount);
                    
                    // Add to agent receive_wallet
                    $agent->increment('receive_wallet', $amount);
                    
                    // Create transaction record for admin (sender)
                    $adminTransition = new Transation();
                    $adminTransition->transationable_id = $this->admin->id;
                    $adminTransition->transationable_type = get_class($this->admin);
                    $adminTransition->amount = $amount;
                    $adminTransition->type = TransitionType::CashOut;
                    // $adminTransition->description = "Transfer to agent: {$agent->name} (ID: {$agent->id})";
                    $adminTransition->save();
                    
                    // Create transaction record for agent (receiver)
                    $agentTransition = new Transation();
                    $agentTransition->transationable_id = $agent->id;
                    $agentTransition->transationable_type = get_class($agent);
                    $agentTransition->amount = $amount;
                    $agentTransition->type = TransitionType::CashIn;
                    // $agentTransition->description = "Received from admin: {$this->admin->name} (ID: {$this->admin->id})";
                    $agentTransition->save();
                    
                    DB::commit();
                    
                    Notification::make()->title('Transfer Successful!')->success()->send();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Notification::make()->title('Transfer Failed: ' . $e->getMessage())->danger()->send();
                }
            });
    }

    public function render()
    {
        return view('livewire.admin-wallet');
    }
}
