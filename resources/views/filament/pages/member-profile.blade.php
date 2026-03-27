<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-4">
            {{ $this->form }}
            <div class="flex gap-3 mt-4">
                {{ ($this->cancelAction) }}
            </div>
        </div>
    </x-filament::section>

    @if ($this->member)
        <div class="space-y-6 mt-6">

            {{-- ── Transfer Button + Edit Button ── --}}
            <div class="flex justify-between items-center">
                {{ $this->editMemberAction }}
                {{ $this->transferMemberAction }}
            </div>

            @if(session('transfer_success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                ✓ {{ session('transfer_success') }}
            </div>
            @endif

            {{-- ── Personal Information ── --}}
            <x-filament::section heading="Personal Information">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="font-semibold">Full Name:</span> {{ $this->member->first_name }} {{ $this->member->middle_name }} {{ $this->member->last_name }}</div>
                    <div><span class="font-semibold">ID Number:</span> {{ $this->member->id_number }}</div>
                    <div><span class="font-semibold">Gender:</span> {{ ucfirst($this->member->gender) }}</div>
                    <div><span class="font-semibold">Date of Birth:</span> {{ $this->member->dob }}</div>
                    <div><span class="font-semibold">Marital Status:</span> {{ ucfirst($this->member->marital_status) }}</div>
                    <div><span class="font-semibold">Mobile:</span> {{ $this->member->mobile_no }}</div>
                    <div><span class="font-semibold">Status:</span>
                        <span class="{{ $this->member->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($this->member->status) }}
                        </span>
                    </div>
                    <div><span class="font-semibold">Group:</span> {{ $this->member->groups->pluck('name')->join(', ') }}</div>
                    <div><span class="font-semibold">Physical Address:</span> {{ $this->member->physical_address }}</div>
                    <div><span class="font-semibold">Village:</span> {{ $this->member->village }}</div>
                    <div><span class="font-semibold">Town:</span> {{ $this->member->town }}</div>
                    <div><span class="font-semibold">County:</span> {{ $this->member->county }}</div>
                </div>
            </x-filament::section>

            {{-- ── Savings Statement ── --}}
            <x-filament::section heading="Savings Statement">
                @if ($this->member->savings->count() > 0)
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="p-2">Date</th>
                                <th class="p-2 text-right">Amount (KES)</th>
                                <th class="p-2">Notes</th>
                                @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
                                <th class="p-2 text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->member->savings as $saving)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-2">{{ $saving->contribution_date }}</td>
                                    <td class="p-2 text-right">{{ number_format($saving->amount, 2) }}</td>
                                    <td class="p-2">{{ $saving->notes ?? '-' }}</td>
                                    @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
                                    <td class="p-2 text-center">
                                        <a href="{{ route('filament.admin.resources.savings.edit', ['record' => $saving->id]) }}"
                                           class="inline-flex items-center gap-1 px-2 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-medium rounded transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-semibold bg-gray-50">
                                <td class="p-2">Total</td>
                                <td class="p-2 text-right">KES {{ number_format($this->member->savings->sum('amount'), 2) }}</td>
                                <td></td>
                                @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
                                <td></td>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p class="text-gray-500 text-sm">No savings records found.</p>
                @endif
            </x-filament::section>

            {{-- ── Loan Statement ── --}}
            <x-filament::section heading="Loan Statement">
                @php
                    $loans = $this->member->loans()->orderBy('created_at', 'desc')->get();
                @endphp
                @if ($loans->count() > 0)
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="p-2">Loan #</th>
                                <th class="p-2 text-right">Principal (KES)</th>
                                <th class="p-2 text-right">Repayable (KES)</th>
                                <th class="p-2 text-right">Balance (KES)</th>
                                <th class="p-2">Status</th>
                                <th class="p-2">Release Date</th>
                                <th class="p-2">Clearance Date</th>
                                @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
                                <th class="p-2 text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loans as $loan)
                                @php
                                    $statusColor = match($loan->loan_status) {
                                        'fully_paid'     => 'text-green-600',
                                        'approved'       => 'text-blue-600',
                                        'partially_paid' => 'text-amber-600',
                                        'defaulted'      => 'text-red-600',
                                        default          => 'text-gray-600',
                                    };
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-2 font-mono text-xs">{{ $loan->loan_number ?? '#'.$loan->id }}</td>
                                    <td class="p-2 text-right">{{ number_format($loan->principal_amount ?? 0, 2) }}</td>
                                    <td class="p-2 text-right">{{ number_format($loan->repayment_amount ?? 0, 2) }}</td>
                                    <td class="p-2 text-right">{{ number_format($loan->balance ?? 0, 2) }}</td>
                                    <td class="p-2 font-medium {{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $loan->loan_status ?? '')) }}</td>
                                    <td class="p-2">{{ $loan->loan_release_date ? \Carbon\Carbon::parse($loan->loan_release_date)->format('d M Y') : '-' }}</td>
                                    <td class="p-2">{{ $loan->clearance_date ? \Carbon\Carbon::parse($loan->clearance_date)->format('d M Y') : '-' }}</td>
                                    @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
                                    <td class="p-2 text-center">
                                        <a href="{{ route('filament.admin.resources.loans.edit', ['record' => $loan->id]) }}"
                                           class="inline-flex items-center gap-1 px-2 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-medium rounded transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-semibold bg-gray-50">
                                <td class="p-2">Totals</td>
                                <td class="p-2 text-right">KES {{ number_format($loans->sum('principal_amount'), 2) }}</td>
                                <td class="p-2 text-right">KES {{ number_format($loans->sum('repayment_amount'), 2) }}</td>
                                <td class="p-2 text-right">KES {{ number_format($loans->sum('balance'), 2) }}</td>
                                <td colspan="{{ (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin')) ? 4 : 3 }}"></td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p class="text-gray-500 text-sm">No loan records found.</p>
                @endif
            </x-filament::section>

            {{-- ── Deposit Statement ── --}}
            <x-filament::section heading="Deposit Account">
                @php
                    $deposits = $this->member->deposits()->orderByDesc('created_at')->get();
                    $depositBalance = $deposits->first()?->balance_after ?? 0;
                @endphp
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm text-gray-500">Current Deposit Balance</span>
                    <span class="text-xl font-bold text-green-600">KES {{ number_format($depositBalance, 2) }}</span>
                </div>
                @if($deposits->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="p-2 text-left font-semibold text-gray-600 dark:text-gray-300">Date</th>
                                <th class="p-2 text-left font-semibold text-gray-600 dark:text-gray-300">Type</th>
                                <th class="p-2 text-left font-semibold text-gray-600 dark:text-gray-300">Amount</th>
                                <th class="p-2 text-left font-semibold text-gray-600 dark:text-gray-300">Balance After</th>
                                <th class="p-2 text-left font-semibold text-gray-600 dark:text-gray-300">Reference</th>
                                <th class="p-2 text-left font-semibold text-gray-600 dark:text-gray-300">Contact</th>
                                <th class="p-2 text-left font-semibold text-gray-600 dark:text-gray-300">Notes</th>
                                <th class="p-2 text-left font-semibold text-gray-600 dark:text-gray-300">Officer</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($deposits as $dep)
                            <tr class="bg-white dark:bg-gray-900">
                                <td class="p-2 text-gray-500">{{ $dep->created_at->format('d M Y H:i') }}</td>
                                <td class="p-2">
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $dep->type === 'credit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($dep->type) }}
                                    </span>
                                </td>
                                <td class="p-2 font-semibold {{ $dep->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $dep->type === 'credit' ? '+' : '-' }} KES {{ number_format($dep->amount, 2) }}
                                </td>
                                <td class="p-2">KES {{ number_format($dep->balance_after, 2) }}</td>
                                <td class="p-2 text-gray-500">{{ $dep->reference ?? '-' }}</td>
                                <td class="p-2 text-gray-500">{{ $dep->contact ?? '-' }}</td>
                                <td class="p-2 text-gray-500">{{ $dep->notes ?? '-' }}</td>
                                <td class="p-2">{{ $dep->createdBy?->name ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-800 font-semibold">
                            <tr>
                                <td colspan="2" class="p-2">Total Credits</td>
                                <td class="p-2 text-green-600">+ KES {{ number_format($deposits->where('type','credit')->sum('amount'), 2) }}</td>
                                <td colspan="5"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <p class="text-gray-500 text-sm">No deposit records found.</p>
                @endif
            </x-filament::section>

            {{-- ── Action Buttons ── --}}
            <div class="flex gap-3 mt-4">
                {{ $this->downloadPdfAction }}
                {{ $this->cancelAction }}
            </div>

        </div>
    @elseif ($this->id_number)
        <div class="mt-6 p-4 bg-red-50 text-red-600 rounded text-sm">
            No member found with ID number: {{ $this->id_number }}
        </div>
    @endif
</x-filament-panels::page>