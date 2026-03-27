<x-filament-panels::page>
<style>
    .rpt-summary { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:1rem; margin-bottom:1.5rem; }
    .rpt-stat { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:1rem 1.25rem; }
    .rpt-stat .val { font-size:1.5rem; font-weight:700; color:#0f172a; margin:0; line-height:1.2; }
    .rpt-stat .lbl { font-size:0.75rem; color:#64748b; margin:0.2rem 0 0; }
    .rpt-stat.green .val { color:#16a34a; }
    .rpt-stat.blue  .val { color:#2563eb; }
    .rpt-stat.amber .val { color:#b45309; }
    .rpt-table-wrap { overflow-x:auto; border-radius:10px; border:1px solid #e2e8f0; }
    table.rpt { width:100%; border-collapse:collapse; font-size:0.85rem; }
    table.rpt thead tr { background:#f1f5f9; }
    table.rpt th { padding:0.65rem 0.85rem; text-align:left; font-weight:700; font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em; color:#475569; white-space:nowrap; }
    table.rpt td { padding:0.6rem 0.85rem; border-top:1px solid #f1f5f9; color:#334155; }
    table.rpt tbody tr:hover { background:#f8fafc; }
    .badge { display:inline-block; padding:0.2rem 0.55rem; border-radius:99px; font-size:0.72rem; font-weight:600; }
    .badge.active  { background:#dcfce7; color:#166534; }
    .badge.inactive{ background:#fee2e2; color:#dc2626; }
    .empty-state { text-align:center; padding:3rem; color:#94a3b8; font-size:0.9rem; }
</style>

{{-- Summary cards --}}
<div class="rpt-summary">
    <div class="rpt-stat">
        <p class="val">{{ $this->totalMembers }}</p>
        <p class="lbl">Total Members</p>
    </div>
    <div class="rpt-stat green">
        <p class="val">{{ $this->activeMembers }}</p>
        <p class="lbl">Active</p>
    </div>
    <div class="rpt-stat blue">
        <p class="val">KES {{ number_format($this->totalSavings, 0) }}</p>
        <p class="lbl">Total Savings</p>
    </div>
    <div class="rpt-stat amber">
        <p class="val">KES {{ number_format($this->totalLoanBal, 0) }}</p>
        <p class="lbl">Active Loan Balance</p>
    </div>
</div>

{{-- Filter bar --}}
@php
    $filterFields = [
        ['type'=>'text',   'wire'=>'search',    'label'=>'Search',       'placeholder'=>'Name / ID / Mobile'],
    ];

    // Officer filter - only for super_admin
    if ($isSuperAdmin && count($officers) > 0) {
        $filterFields[] = [
            'type'=>'select', 'wire'=>'officer_id', 'label'=>'Officer', 'placeholder'=>'All Officers',
            'options'=> collect($officers)->pluck('name','id')->toArray(),
        ];
    }

    $filterFields = array_merge($filterFields, [
        ['type'=>'select', 'wire'=>'group_id',  'label'=>'Group',        'placeholder'=>'All Groups',
         'options'=> collect($groups)->pluck('name','id')->toArray()],
        ['type'=>'select', 'wire'=>'status',    'label'=>'Status',       'placeholder'=>'All Statuses',
         'options'=>['active'=>'Active','inactive'=>'Inactive']],
        ['type'=>'select', 'wire'=>'gender',    'label'=>'Gender',       'placeholder'=>'All',
         'options'=>['male'=>'Male','female'=>'Female']],
        ['type'=>'date',   'wire'=>'date_from', 'label'=>'Joined From'],
        ['type'=>'date',   'wire'=>'date_to',   'label'=>'Joined To'],
    ]);
@endphp

@include('filament.pages.reports._filter-bar', ['fields' => $filterFields])

{{-- Table --}}
<div class="rpt-table-wrap">
    @if(count($members) > 0)
    <table class="rpt">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>ID Number</th>
                <th>Mobile</th>
                <th>Group</th>
                @if($isSuperAdmin)
                <th>Officer</th>
                @endif
                <th>Gender</th>
                <th>Status</th>
                <th>Joined</th>
                <th style="text-align:right">Savings (KES)</th>
                <th style="text-align:right">Loan Balance (KES)</th>
                <th style="text-align:right">Active Loans</th>
                <th style="text-align:right">Deposit (KES)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $i => $m)
            <tr>
                <td style="color:#94a3b8">{{ $i+1 }}</td>
                <td style="font-weight:600">{{ $m['name'] }}</td>
                <td style="font-family:monospace">{{ $m['id_number'] }}</td>
                <td>{{ $m['mobile'] }}</td>
                <td>{{ $m['group'] }}</td>
                @if($isSuperAdmin)
                <td>{{ $m['officer'] }}</td>
                @endif
                <td>{{ $m['gender'] }}</td>
                <td><span class="badge {{ $m['status'] }}">{{ ucfirst($m['status']) }}</span></td>
                <td>{{ $m['joined'] }}</td>
                <td style="text-align:right">{{ number_format($m['savings'],2) }}</td>
                <td style="text-align:right">{{ number_format($m['loan_balance'],2) }}</td>
                <td style="text-align:right">{{ $m['loan_count'] }}</td>
                <td style="text-align:right;color:#16a34a;font-weight:600">{{ number_format($m['deposit'] ?? 0,2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f1f5f9;font-weight:700;">
                <td colspan="{{ $isSuperAdmin ? 9 : 8 }}" style="padding:0.65rem 0.85rem;">TOTALS</td>
                <td style="text-align:right;padding:0.65rem 0.85rem;">{{ number_format($this->totalSavings,2) }}</td>
                <td style="text-align:right;padding:0.65rem 0.85rem;">{{ number_format($this->totalLoanBal,2) }}</td>
                <td></td>
                <td style="text-align:right;padding:0.65rem 0.85rem;color:#16a34a;">{{ number_format($this->totalDeposit,2) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
    <div class="empty-state">No members found matching the selected filters.</div>
    @endif
</div>
</x-filament-panels::page>
