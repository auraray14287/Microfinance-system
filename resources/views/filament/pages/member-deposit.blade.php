<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-4">
            {{ $this->form }}
            <div class="flex gap-3 mt-2">
                {{ $this->cancelAction }}
            </div>
        </div>
    </x-filament::section>

    @if ($this->member)
    <div class="space-y-6 mt-4">

        {{-- Member Info + Deposit Balance --}}
        <x-filament::section>
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                        {{ $this->member->first_name }} {{ $this->member->middle_name }} {{ $this->member->last_name }}
                    </h3>
                    <p class="text-sm text-gray-500">ID: {{ $this->member->id_number }} &nbsp;|&nbsp; Group: {{ $this->member->groups?->first()?->name ?? '-' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Deposit Balance</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        KES {{ number_format($this->depositBalance ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </x-filament::section>

        {{-- Add Deposit Form --}}
        <x-filament::section heading="Add Deposit">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Amount (KES) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" wire:model="amount" min="1"
                        placeholder="Enter amount"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-900 dark:text-white focus:ring-primary-500" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Transaction Reference <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="reference"
                        placeholder="e.g. MPESA code"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-900 dark:text-white focus:ring-primary-500" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Contact <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="contact"
                        placeholder="e.g. phone number"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-900 dark:text-white focus:ring-primary-500" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Notes
                    </label>
                    <input type="text" wire:model="notes"
                        placeholder="Optional notes"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-900 dark:text-white focus:ring-primary-500" />
                </div>
            </div>
            @if($saveError)
            <p class="mt-2 text-sm text-red-600">{{ $saveError }}</p>
            @endif
            @if($saved)
            <p class="mt-2 text-sm text-green-600">✓ Deposit recorded successfully.</p>
            @endif
            <div class="mt-4">
                <x-filament::button wire:click="addDeposit" color="success">
                    Save Deposit
                </x-filament::button>
            </div>
        </x-filament::section>

        {{-- Pending Loans needing deposit --}}
        @if(count($this->pendingLoans) > 0)
        <x-filament::section heading="Pending Loans Awaiting Deposit">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Loan #</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Principal</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Required Deposit</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Balance Needed</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($this->pendingLoans as $loan)
                        <tr class="bg-white dark:bg-gray-900">
                            <td class="px-4 py-2 font-medium">{{ $loan['loan_number'] }}</td>
                            <td class="px-4 py-2">KES {{ number_format($loan['principal'], 2) }}</td>
                            <td class="px-4 py-2">KES {{ number_format($loan['required'], 2) }}</td>
                            <td class="px-4 py-2 {{ ($loan['shortfall'] ?? 0) == 0 ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                                @if(($loan['shortfall'] ?? 0) == 0)
                                    ✓ Fully paid
                                @else
                                    KES {{ number_format($loan['shortfall'] ?? 0, 2) }} short
                                    @if(($loan['wallet'] ?? 0) > 0)
                                        <br><span class="text-xs text-blue-600">(Wallet: KES {{ number_format($loan['wallet'], 2) }} available)</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $loan['status'] === 'approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($loan['status']) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
        @endif

        {{-- Deposit History --}}
        @if(count($this->depositHistory) > 0)
        <x-filament::section heading="Deposit History">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Date</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Type</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Amount</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Balance After</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Notes</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Reference</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Contact</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Officer</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Loan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($this->depositHistory as $entry)
                        <tr class="bg-white dark:bg-gray-900">
                            <td class="px-4 py-2 text-gray-500">{{ $entry['date'] }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $entry['type'] === 'credit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($entry['type']) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 font-semibold
                                {{ $entry['type'] === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $entry['type'] === 'credit' ? '+' : '-' }} KES {{ number_format($entry['amount'], 2) }}
                            </td>
                            <td class="px-4 py-2">KES {{ number_format($entry['balance_after'], 2) }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $entry['notes'] }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $entry['reference'] }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $entry['contact'] }}</td>
                            <td class="px-4 py-2">{{ $entry['officer'] }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $entry['loan'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
        @endif

    </div>
    @endif
</x-filament-panels::page>
