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

    public function render()
    {
        return view('livewire.admin-wallet');
    }
}
