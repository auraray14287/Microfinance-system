<x-filament-panels::page>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 1 – GROUP SEARCH WITH LIVE AUTOCOMPLETE
    ═══════════════════════════════════════════════════════════ --}}
    <x-filament::section heading="Group Payment Entry">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

            {{-- Search input with dropdown --}}
            <div class="md:col-span-2 relative" x-data="{ open: @entangle('showSuggestions') }">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Group Name
                </label>

                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="group_search"
                        wire:keydown.enter="searchGroup"
                        wire:keydown.escape="$set('showSuggestions', false)"
                        placeholder="Start typing a group name…"
                        autocomplete="off"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 pr-8 text-sm
                               focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white"
                    />
                    <div wire:loading wire:target="updatedGroupSearch"
                         class="absolute right-2.5 top-2.5 text-gray-400">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                    </div>
                </div>

                @if($showSuggestions && count($groupSuggestions) > 0)
                <div class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600
                            rounded-lg shadow-xl overflow-hidden">
                    @foreach($groupSuggestions as $suggestion)
                    <button
                        type="button"
                        wire:click="selectGroupSuggestion({{ $suggestion['id'] }})"
                        class="w-full text-left px-4 py-2.5 hover:bg-primary-50 dark:hover:bg-primary-900/30
                               border-b last:border-0 border-gray-100 dark:border-gray-700 transition-colors"
                    >
                        <span class="block font-medium text-sm text-gray-900 dark:text-white">
                            {{ $suggestion['name'] }}
                        </span>
                        @if($suggestion['sub'])
                        <span class="block text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                            {{ $suggestion['sub'] }}
                        </span>
                        @endif
                    </button>
                    @endforeach
                </div>
                @endif

                @if($groupSearchError)
                    <p class="mt-1.5 text-sm text-danger-600 dark:text-danger-400">
                        {{ $groupSearchError }}
                    </p>
                @endif
            </div>

            <div class="flex gap-2">
                <x-filament::button wire:click="searchGroup">
                    Search
                </x-filament::button>
                @if($selectedGroup)
                    @if($selectedGroup)
                    <x-filament::button
                        tag="a"
                        href="{{ route('group-payment.blank', ['group' => $selectedGroup->id]) }}"
                        target="_blank"
                        color="gray"
                        icon="heroicon-o-arrow-down-tray"
                    >
                        Download Blank Form
                    </x-filament::button>
                    @endif
                <x-filament::button wire:click="resetAll" color="gray">
                    Clear
                </x-filament::button>
                @endif
            </div>
        </div>

        @if($selectedGroup)
        <div class="mt-4 p-3 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-700
                    rounded-lg flex flex-wrap gap-6 text-sm items-center">
            <div>
                <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide block">Group</span>
                <p class="font-bold text-primary-700 dark:text-primary-300 text-base">{{ $selectedGroup->name }}</p>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide block">Reg. No.</span>
                <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $selectedGroup->registration_number ?? '—' }}</p>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide block">Location</span>
                <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $selectedGroup->location ?? '—' }}</p>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide block">Active Members</span>
                <p class="font-semibold text-gray-700 dark:text-gray-200">{{ count($paymentRows) }}</p>
            </div>
            <div>
                <label class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide block">Payment Date</label>
                <input type="date" wire:model="paymentDate"
                    class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-sm
                           dark:bg-gray-800 dark:text-white" />
            </div>
        </div>
        @endif
    </x-filament::section>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 2 – PAYMENT TABLE
    ═══════════════════════════════════════════════════════════ --}}
    @if($selectedGroup && count($paymentRows) > 0 && !$saved)
    <x-filament::section heading="Member Payment Entry — {{ $selectedGroup->name }}">

        @if($saveError)
        <div class="mb-4 p-3 bg-danger-50 border border-danger-200 text-danger-700 rounded-lg text-sm">
            {{ $saveError }}
        </div>
        @endif

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <th class="px-3 py-3 font-semibold border-b dark:border-gray-600 w-8">#</th>
                        <th class="px-3 py-3 font-semibold border-b dark:border-gray-600">Member Name</th>
                        <th class="px-3 py-3 font-semibold border-b dark:border-gray-600">ID Number</th>
                        <th class="px-3 py-3 font-semibold border-b dark:border-gray-600">Group</th>
                        <th class="px-3 py-3 font-semibold border-b dark:border-gray-600 text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/20">
                            Savings (KES)
                        </th>
                        <th class="px-3 py-3 font-semibold border-b dark:border-gray-600 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20">
                            Loan Payment (KES)
                        </th>
                        <th class="px-3 py-3 font-semibold border-b dark:border-gray-600 text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/20">
                            Overdue Payment (KES)
                        </th>
                        <th class="px-3 py-3 font-semibold border-b dark:border-gray-600 text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/20">
                            Expected Installment (KES)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentRows as $index => $row)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                        <td class="px-3 py-2 text-gray-400 text-xs">{{ $index + 1 }}</td>
                        <td class="px-3 py-2 font-medium text-gray-900 dark:text-white">{{ $row['name'] }}</td>
                        <td class="px-3 py-2 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $row['id_number'] }}</td>
                        <td class="px-3 py-2 text-gray-500 dark:text-gray-400 text-xs">{{ $row['group_name'] }}</td>

                        <td class="px-3 py-2 bg-emerald-50/40 dark:bg-emerald-900/10">
                            <input type="number" wire:model.lazy="paymentRows.{{ $index }}.savings_input"
                                min="0" step="1" placeholder="0"
                                class="w-full min-w-[90px] border border-emerald-300 dark:border-emerald-600 rounded px-2 py-1.5
                                       text-sm text-right focus:ring-2 focus:ring-emerald-400 dark:bg-gray-800 dark:text-white" />
                        </td>

                        <td class="px-3 py-2 bg-blue-50/40 dark:bg-blue-900/10">
                            @if($row['has_active_loans'])
                            <input type="number" wire:model.lazy="paymentRows.{{ $index }}.loan_input"
                                min="0" step="1" placeholder="0"
                                class="w-full min-w-[90px] border border-blue-300 dark:border-blue-600 rounded px-2 py-1.5
                                       text-sm text-right focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:text-white" />
                            @else
                            <span class="text-xs text-gray-300 dark:text-gray-600 italic">No active loans</span>
                            @endif
                        </td>

                        <td class="px-3 py-2 bg-red-50/40 dark:bg-red-900/10">
                            @if(($row['overdue'] ?? 0) > 0)
                                <div class="text-right font-semibold text-red-600 dark:text-red-400">
                                    {{ number_format($row['overdue'], 2) }}
                                </div>
                            @else
                                <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 bg-amber-50/40 dark:bg-amber-900/10">
                            @if($row['has_active_loans'])
                                <div class="text-right font-semibold text-amber-700 dark:text-amber-300">
                                    {{ number_format($row['expected_loan'], 2) }}
                                </div>
                                @if(count($row['loan_details']) > 1)
                                <div class="mt-1 space-y-0.5 border-t border-amber-200 dark:border-amber-800 pt-1">
                                    @foreach($row['loan_details'] as $ld)
                                    <div class="flex justify-between text-xs text-gray-400 dark:text-gray-500">
                                        <span class="font-mono">{{ $ld['loan_number'] }}</span>
                                        <span>{{ number_format($ld['amount_per_installment'], 2) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            @else
                                <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                        <td class="px-3 py-3 text-gray-700 dark:text-gray-200 text-sm" colspan="4">
                            TOTALS
                            <span class="font-normal text-xs text-gray-500">({{ count($paymentRows) }} members)</span>
                        </td>
                        <td class="px-3 py-3 text-right text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/30">
                            KES {{ number_format($this->totalSavings, 2) }}
                        </td>
                        <td class="px-3 py-3 text-right text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30">
                            KES {{ number_format($this->totalLoanPayments, 2) }}
                        </td>
                        <td class="px-3 py-3 text-right text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30">
                            KES {{ number_format($this->totalExpectedLoan, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- TRANSACTION CODE + BUTTONS --}}
        <div class="mt-5 p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Transaction Code <span class="text-danger-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model.defer="transaction_code"
                        placeholder="Enter M-Pesa / Bank transaction code"
                        class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-900 dark:text-white
                               {{ $transactionCodeError
                                   ? 'border-danger-500 focus:ring-danger-400'
                                   : 'border-gray-300 dark:border-gray-600 focus:ring-primary-500' }}"
                    />
                    @if($transactionCodeError)
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400 font-medium">
                            {{ $transactionCodeError }}
                        </p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Contact Used to Send Money <span class="text-danger-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model.defer="contact"
                        placeholder="e.g. 0712345678"
                        class="w-full border rounded-lg px-3 py-2 text-sm dark:bg-gray-900 dark:text-white
                               {{ $contactError
                                   ? 'border-danger-500 focus:ring-danger-400'
                                   : 'border-gray-300 dark:border-gray-600 focus:ring-primary-500' }}"
                    />
                    @if($contactError)
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400 font-medium">
                            {{ $contactError }}
                        </p>
                    @endif
                </div>

                <div class="flex gap-3">
                    <x-filament::button
                        wire:click="savePayments"
                        wire:loading.attr="disabled"
                        color="success"
                    >
                        <span wire:loading.remove wire:target="savePayments">Save and Generate Report</span>
                        <span wire:loading wire:target="savePayments">Saving payments...</span>
                    </x-filament::button>

                    <x-filament::button wire:click="resetAll" color="gray">
                        Cancel
                    </x-filament::button>
                </div>
            </div>
        </div>
    </x-filament::section>
    @endif

    {{-- Empty group message --}}
    @if($selectedGroup && count($paymentRows) === 0 && !$saved)
    <x-filament::section>
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">
            This group has no active members.
        </p>
    </x-filament::section>
    @endif

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 3 – SUCCESS + PDF DOWNLOAD
    ═══════════════════════════════════════════════════════════ --}}
    @if($saved && $showPdfReady)
    <x-filament::section>
        <div class="text-center py-10 space-y-3">
            <div class="text-5xl">✅</div>
            <h2 class="text-xl font-bold text-success-600 dark:text-success-400">
                Payments saved successfully!
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                All savings and loan payments for
                <strong class="text-gray-700 dark:text-gray-200">{{ $selectedGroup->name }}</strong>
                on <strong class="text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($paymentDate)->format('d M Y') }}</strong>
                have been recorded with transaction code
                <strong class="text-gray-700 dark:text-gray-200">{{ $transaction_code }}</strong>.
            </p>

            <div class="flex justify-center gap-4 mt-6 flex-wrap">
                <x-filament::button
                    tag="a"
                    href="{{ route('group-payment.pdf', ['group' => $selectedGroup->id, 'date' => $paymentDate]) }}"
                    target="_blank"
                >
                    Download PDF Report
                </x-filament::button>

                <x-filament::button wire:click="resetAll" color="gray">
                    New Payment Entry
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
    @endif

</x-filament-panels::page>
