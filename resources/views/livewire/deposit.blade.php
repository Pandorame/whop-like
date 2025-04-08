<div class=" ">
    <div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Deposit Funds</h2>
                <p class="text-slate-300 text-sm"> Deposit funds with your selected agents and easy payment </p>
            </div>
        
          <div class="p-4">
            <form wire:submit.prevent="makeDeposit">    
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                    <!-- Agent Selection -->
                    <div class="col-span-1">
                        <label for="agent" class="block text-sm font-medium text-gray-700 mb-1">Select Agent</label>
                        <div class="relative">
                            <select id="agent"  wire:model.live="selectedAgent" class="block border px-3  w-full  pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm rounded-md">
                                <option value="">Select Agent</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('selectedAgent') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    @if(!empty($payments))
                    <!-- Payment Selection -->
                    <div class="col-span-1" x-data="{ open: false }">
                        <label for="payment" class="block text-sm font-medium text-gray-700 mb-1">Select Payment</label>
                        <div class="relative">
                            <select id="payment"  wire:model.live="selectedPayment" class="block border w-full px-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm rounded-md">
                                <option value="">Payment Method</option>
                                @foreach($payments as $payment)
                                    <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('selectedPayment') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>
                
                @if($selectedPayment && !empty($paymentAccounts))
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Payment Account</label>
                    <div class="grid grid-cols-1  gap-4">
                        @foreach($paymentAccounts as $account)
                            <label for="account-{{ $account->id }}" class="relative cursor-pointer" x-data="{ showCopied: false }">
                                <input type="radio" id="account-{{ $account->id }}" wire:model.live="selectedPaymentAccount" value="{{ $account->id }}" class="sr-only">
                                <div class="border rounded-lg p-4 transition-all duration-200 ease-in-out hover:shadow-md 
                                    {{ $selectedPaymentAccount == $account->id ? 'bg-slate-50 border-slate-500 ring-1 ring-slate-500' : 'border-gray-300' }}">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $selectedPaymentAccount == $account->id ? 'text-slate-600' : 'text-gray-400' }} h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium {{ $selectedPaymentAccount == $account->id ? 'text-slate-900' : 'text-gray-900' }}">
                                                {{ $account->name }}
                                            </h3>
                                            <p class="mt-1 text-xs {{ $selectedPaymentAccount == $account->id ? 'text-slate-700' : 'text-gray-500' }}">
                                                {{ Str::limit($account->number, 20, '...')  }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($selectedPaymentAccount == $account->id)
                                        <div class="absolute top-2 right-2 flex gap-1">
                                            <button type="button" 
                                                @click="
                                                    navigator.clipboard.writeText('{{ $account->number }}');
                                                    showCopied = true;
                                                    setTimeout(() => showCopied = false, 2000);
                                                "
                                                class="text-slate-600 hover:text-slate-800" 
                                                title="Copy account number"
                                                x-show="!showCopied">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                </svg>
                                            </button>
                                            <span x-show="showCopied" class="text-xs text-green-600">Copied!</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedPaymentAccount') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                </div>
                    
                    <div class="grid grid-cols-1  gap-6 mb-6">
                        <div class="col-span-1">
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                            <div class="mt-1 relative rounded-md s">
                                <input type="number" id="amount" wire:model="amount" class="focus:ring-slate-500 p-2 border focus:outline-0   focus:border-slate-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00" step="0.01" min="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Kyats</span>
                                </div>
                            </div>
                            @error('amount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-1 ">
                            <label for="slip" class="block text-sm font-medium text-gray-700 mb-1">Upload Payment Slip</label>
                            <label for="file-upload" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 relative border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    @if($slip)
                                        <div class="mb-3">
                                            <img src="{{ $slip->temporaryUrl() }}" class="mx-auto h-32 object-cover rounded-md" alt="Slip preview">
                                        </div>
                                        <button type="button" wire:click="removeSlip" class="text-sm text-red-600 hover:text-red-800">
                                            Remove
                                        </button>
                                    @else
                                        <input id="file-upload" wire:model.live="slip" type="file" class="sr-only " accept="image/*">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <div class="relative  cursor-pointer bg-white rounded-md w-full h-full font-medium text-slate-600 hover:text-slate-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-slate-500">
                                                <span>Upload a file or drag and drop</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, GIF up to 2MB
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div wire:loading wire:target="slip" class="mt-2 text-sm text-slate-600">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-slate-600 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </div>
                            @error('slip') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
    
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                Process Deposit
                            </button>
                        </div>
                    </div>
                    
                @endif
            </form>
            
            @if(session()->has('message'))
                <div class="mt-6 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(session()->has('error'))
                <div class="mt-6 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            </div>

        </div>
    </div>
</div>