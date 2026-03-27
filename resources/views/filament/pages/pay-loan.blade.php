<x-filament-panels::page>

    {{-- Panel A: Member Search --}}
    <x-filament::section heading="Member Lookup">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Member ID Number</label>
                <input
                    type="text"
                    wire:model.blur="member_id_number"
                    wire:keydown.enter="searchMember"
                    placeholder="Enter Member ID Number and press Enter"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white"
                />
                <button wire:click="searchMember" class="mt-2 px-4 py-2 bg-primary-600 text-white rounded-lg text-sm hover:bg-primary-700">
                    Search
                </button>
                @if($selectedMember === null && $member_id_number)
                    <p class="mt-1 text-sm text-danger-600">No member found with this ID in your assigned groups.</p>
                @endif
            </div>

            @if($selectedMember)
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $selectedMember->first_name }} {{ $selectedMember->middle_name }} {{ $selectedMember->last_name }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $selectedMember->id_number }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Group: {{ $selectedMember->groups->pluck('name')->join(', ') ?: 'N/A' }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Mobile: {{ $selectedMember->mobile_no }}</p>
                <button wire:click="resetAll" class="mt-2 text-xs text-gray-400 hover:text-danger-600 underline">
                    Clear / Search Another Member
                </button>
            </div>
            @endif
        </div>

        @if($successMessage)
        <div class="mt-4 p-3 bg-success-50 border border-success-200 text-success-700 rounded-lg text-sm">
            ✅ {{ $successMessage }}
        </div>
        @endif
    </x-filament::section>

    {{-- Panel B: Loans List --}}
    @if($selectedMember && !$selectedLoan)
    <x-filament::section heading="Member Loans">
        @if($selectedMember->loans->isEmpty())
            <p class="text-sm text-gray-500">This member has no active loans.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="p-3">Loan ID</th>
                        <th class="p-3">Loan Type</th>
                        <th class="p-3">Total Amount (KES)</th>
                        <th class="p-3">Balance (KES)</th>
                        <th class="p-3">Next Installment</th>
                        <th class="p-3">Installment Amount (KES)</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedMember->loans as $loan)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-3 font-mono text-xs">{{ $loan->loan_number ?? '#'.$loan->id }}</td>
                        <td class="p-3">{{ $loan->loan_type?->loan_name ?? 'N/A' }}</td>
                        <td class="p-3">{{ number_format($loan->repayment_amount, 2) }}</td>
                        <td class="p-3 font-semibold text-danger-600">{{ number_format($loan->balance, 2) }}</td>
                        <td class="p-3">{{ $loan->next_payment_date ?? 'N/A' }}</td>
                        <td class="p-3">{{ number_format($loan->amount_per_installment ?? 0, 2) }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $loan->loan_status === 'defaulted' ? 'bg-danger-100 text-danger-700' : '' }}
                                {{ $loan->loan_status === 'approved' ? 'bg-success-100 text-success-700' : '' }}
                                {{ $loan->loan_status === 'partially_paid' ? 'bg-warning-100 text-warning-700' : '' }}
                                {{ $loan->loan_status === 'fully_paid' ? 'bg-gray-100 text-gray-600' : '' }}
                            ">
                                {{ ucfirst(str_replace('_', ' ', $loan->loan_status)) }}
                                @if($loan->loan_status === 'defaulted') ⚠️ @endif
                            </span>
                        </td>
                        <td class="p-3">
                            @if(!in_array($loan->loan_status, ['fully_paid', 'denied']))
                            <button
                                wire:click="selectLoan({{ $loan->id }})"
                                class="px-3 py-1.5 bg-primary-600 text-white text-xs rounded-lg hover:bg-primary-700"
                            >
                                Pay
                            </button>
                            @else
                            <span class="text-xs text-gray-400">Closed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </x-filament::section>
    @endif

    {{-- Panel C: Payment Form --}}
    @if($selectedLoan)
    <x-filament::section heading="Record Payment">

        {{-- Loan summary (read-only) --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Loan ID</p>
                <p class="font-semibold text-sm">{{ $selectedLoan->loan_number ?? '#'.$selectedLoan->id }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Loan Type</p>
                <p class="font-semibold text-sm">{{ $selectedLoan->loan_type?->loan_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total Repayment</p>
                <p class="font-semibold text-sm">KES {{ number_format($selectedLoan->repayment_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Outstanding Balance</p>
                <p class="font-bold text-danger-600 text-lg">KES {{ number_format($selectedLoan->balance, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Next Installment Date</p>
                <p class="font-semibold text-sm">{{ $selectedLoan->next_payment_date ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Installment Amount</p>
                <p class="font-semibold text-sm">KES {{ number_format($selectedLoan->amount_per_installment ?? 0, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    {{ $selectedLoan->loan_status === 'defaulted' ? 'bg-danger-100 text-danger-700' : 'bg-success-100 text-success-700' }}
                ">
                    {{ ucfirst(str_replace('_', ' ', $selectedLoan->loan_status)) }}
                    @if($selectedLoan->loan_status === 'defaulted') ⚠️ DEFAULT @endif
                </span>
            </div>
        </div>

        {{-- Success/Error messages --}}
        @if($errorMessage)
        <div class="mb-4 p-3 bg-danger-50 border border-danger-200 text-danger-700 rounded-lg text-sm">
            ❌ {{ $errorMessage }}
        </div>
        @endif

        {{-- Payment form --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Amount Paid (KES) <span class="text-danger-600">*</span>
                </label>
                <input
                    type="number"
                    wire:model="paymentAmount"
                    step="0.01"
                    min="0.01"
                    max="{{ $selectedLoan->balance }}"
                    placeholder="Enter amount paid"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white"
                />
                <p class="text-xs text-gray-400 mt-1">Max: KES {{ number_format($selectedLoan->balance, 2) }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Payment Method <span class="text-danger-600">*</span>
                </label>
                <select
                    wire:model="paymentMethod"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white"
                >
                    <option value="">— Select Method —</option>
                    <option value="cash">Cash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="pemic">PEMIC</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Transaction Reference (optional)
                </label>
                <input
                    type="text"
                    wire:model="referenceNumber"
                    placeholder="Enter reference number"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white"
                />
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button
                wire:click="submitPayment"
                wire:loading.attr="disabled"
                class="px-6 py-2 bg-success-600 text-white rounded-lg font-semibold text-sm hover:bg-success-700 disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="submitPayment">✅ Record Payment</span>
                <span wire:loading wire:target="submitPayment">Processing...</span>
            </button>
            <button wire:click="goBack" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300">
                ← Back to Loans
            </button>
        </div>
    </x-filament::section>
    @endif

</x-filament-panels::page>
