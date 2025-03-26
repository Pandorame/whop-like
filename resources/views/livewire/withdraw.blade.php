<div>
    <div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Withdraw Funds</h2>
                <p class="text-slate-300 text-sm">Withdraw money from your wallet to your preferred payment method</p>
            </div>
            
            <!-- Available Balance -->
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600">Available Balance</span>
                    <span class="text-lg font-bold text-slate-800">{{ number_format($availableBalance) }} Kyats</span>
                </div>
            </div>
            
            <!-- Form -->
            <form wire:submit.prevent="withdraw" class="px-6 py-4 space-y-6">
                @if (session()->has('message'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if (session()->has('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Withdraw Amount</label>
                    <div class="mt-1 relative rounded-md border  p-2   border-gray-300">
                        <input type="text" wire:model.live.blur="amount"  id="amount" class="block focus:ring-0 focus:outline-0  w-full pr-12 sm:text-sm  rounded-md" placeholder="0">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Kyats</span>
                        </div>
                    </div>
                    @error('amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Commission Info -->
                @if($amount && is_numeric($amount) && $amount > 0)
                <div class="bg-slate-50 rounded-lg p-4">
                    <h4 class="text-lg font-medium text-slate-700 mb-2">Withdrawal Summary</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Requested Amount</span>
                            <span class="font-medium">{{ number_format($amount) }} Kyats</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Commission ({{ $commissionRate }}%)</span>
                            <span class="font-medium text-red-600">- {{ number_format($commissionAmount) }} Kyats</span>
                        </div>
                        <div class="pt-2 border-t border-slate-200 flex justify-between">
                            <span class="font-medium text-slate-700">You Will Receive</span>
                            <span class="font-bold text-slate-800">{{ number_format($amountAfterCommission) }} Kyats</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Agent Select -->
                <div>
                    <label for="payment-method" class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
                    <select id="payment-method" wire:model="agentId" class="mt-1 border  block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm rounded-md">
                        <option value="">Select Agent</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                    @error('agentId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Payment Method -->
                <div>
                    <label for="payment-method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select id="payment-method" wire:model="paymentMethod" class="mt-1 border block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm rounded-md">
                        <option value="">Select Payment Method</option>
                        @foreach($payments as $payment)
                            <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                        @endforeach
                    </select>
                    @error('paymentMethod') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Account Number -->
                <div>
                    <label for="account-number" class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                    <input type="text" wire:model="accountNumber" id="account-number" class="mt-1 border focus:ring-slate-500 focus:outline-0 focus:border-slate-500 block w-full p-2  sm:text-sm border-gray-300 rounded-md" placeholder="Enter your account number">
                    @error('accountNumber') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Account Name -->
                <div>
                    <label for="account-name" class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                    <input type="text" wire:model="accountName" id="account-name" class="mt-1 border focus:ring-slate-500 focus:outline-0 focus:border-slate-500 block w-full p-2  sm:text-sm border-gray-300 rounded-md" placeholder="Enter account holder name">
                    @error('accountName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md p-2  text-sm font-medium text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Request Withdrawal
                    </button>
                </div>
                
                <!-- Note -->
                <div class="text-xs text-slate-500 mt-4">
                    <p>Note: Withdrawal requests are processed within 24 hours. A {{ $commissionRate }}% commission fee applies to all withdrawals.</p>
                </div>
            </form>
        </div>
    </div>
</div>
