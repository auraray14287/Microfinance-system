<x-filament-panels::page>
<style>
    .rpt-summary{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;margin-bottom:1.5rem;}
    .rpt-stat{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:1rem 1.25rem;}
    .rpt-stat .val{font-size:1.4rem;font-weight:700;color:#0f172a;margin:0;line-height:1.2;}
    .rpt-stat .lbl{font-size:0.75rem;color:#64748b;margin:0.2rem 0 0;}
    .rpt-stat.green .val{color:#16a34a;} .rpt-stat.blue .val{color:#2563eb;} .rpt-stat.amber .val{color:#b45309;}
    .rpt-table-wrap{overflow-x:auto;border-radius:10px;border:1px solid #e2e8f0;}
    table.rpt{width:100%;border-collapse:collapse;font-size:0.84rem;}
    table.rpt thead tr{background:#f1f5f9;}
    table.rpt th{padding:0.65rem 0.85rem;text-align:left;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;color:#475569;white-space:nowrap;}
    table.rpt td{padding:0.6rem 0.85rem;border-top:1px solid #f1f5f9;color:#334155;}
    table.rpt tbody tr:hover{background:#f8fafc;}
    .badge{display:inline-block;padding:0.18rem 0.55rem;border-radius:99px;font-size:0.72rem;font-weight:600;}
    .badge.approved{background:#dbeafe;color:#1d4ed8;} .badge.partially_paid{background:#fef9c3;color:#a16207;}
    .badge.fully_paid{background:#dcfce7;color:#166534;} .badge.defaulted{background:#fee2e2;color:#dc2626;}
    .empty-state{text-align:center;padding:3rem;color:#94a3b8;font-size:0.9rem;}
</style>

<div class="rpt-summary">
    <div class="rpt-stat"><p class="val">{{ $this->totalLoans }}</p><p class="lbl">Total Loans</p></div>
    <div class="rpt-stat blue"><p class="val">KES {{ number_format($this->totalDisbursed,0) }}</p><p class="lbl">Total Disbursed</p></div>
    <div class="rpt-stat green"><p class="val">KES {{ number_format($this->totalRepaid,0) }}</p><p class="lbl">Total Repaid</p></div>
    <div class="rpt-stat amber"><p class="val">KES {{ number_format($this->totalBalance,0) }}</p><p class="lbl">Outstanding Balance</p></div>
</div>

@include('filament.pages.reports._filter-bar', ['fields' => [
    ['type'=>'text',   'wire'=>'search',    'label'=>'Search',      'placeholder'=>'Name / Loan # / ID'],
    ['type'=>'select', 'wire'=>'group_id',  'label'=>'Group',       'placeholder'=>'All Groups',
     'options'=> collect($groups)->pluck('name','id')->toArray()],
    ['type'=>'select', 'wire'=>'status',    'label'=>'Status',      'placeholder'=>'All Statuses',
     'options'=>['approved'=>'Approved','partially_paid'=>'Partially Paid','fully_paid'=>'Fully Paid','defaulted'=>'Defaulted']],
    ['type'=>'select', 'wire'=>'loan_type', 'label'=>'Loan Type',   'placeholder'=>'All Types',
     'options'=> collect($loanTypes)->pluck('loan_name','id')->toArray()],
    ['type'=>'date',   'wire'=>'date_from', 'label'=>'Disbursed From'],
    ['type'=>'date',   'wire'=>'date_to',   'label'=>'Disbursed To'],
]])

<div class="rpt-table-wrap">
    @if(count($loans) > 0)
    <table class="rpt">
        <thead>
            <tr>
                <th>#</th><th>Loan #</th><th>Member</th><th>ID No.</th><th>Group</th>
                <th>Type</th><th>Status</th><th>Disbursed</th><th>Due</th>
                <th style="text-align:right">Principal (KES)</th>
                <th style="text-align:right">Balance (KES)</th>
                <th style="text-align:right">Installment (KES)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $i => $l)
            <tr>
                <td style="color:#94a3b8">{{ $i+1 }}</td>
                <td style="font-family:monospace;font-weight:600">{{ $l['loan_number'] }}</td>
                <td>{{ $l['member_name'] }}</td>
                <td style="font-family:monospace">{{ $l['id_number'] }}</td>
                <td>{{ $l['group'] }}</td>
                <td>{{ $l['loan_type'] }}</td>
                <td><span class="badge {{ $l['status'] }}">{{ ucfirst(str_replace('_',' ',$l['status'])) }}</span></td>
                <td>{{ $l['disbursed'] }}</td>
                <td>{{ $l['due'] }}</td>
                <td style="text-align:right">{{ number_format($l['principal'],2) }}</td>
                <td style="text-align:right">{{ number_format($l['balance'],2) }}</td>
                <td style="text-align:right">{{ number_format($l['installment'],2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f1f5f9;font-weight:700;">
                <td colspan="9" style="padding:0.65rem 0.85rem;">TOTALS ({{ count($loans) }} loans)</td>
                <td style="text-align:right;padding:0.65rem 0.85rem;">{{ number_format($this->totalDisbursed,2) }}</td>
                <td style="text-align:right;padding:0.65rem 0.85rem;">{{ number_format($this->totalBalance,2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @else
    <div class="empty-state">No loans found matching the selected filters.</div>
    @endif
</div>
</x-filament-panels::page>