<x-filament-panels::page>
<style>
    .rpt-summary{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;margin-bottom:1.5rem;}
    .rpt-stat{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:1rem 1.25rem;}
    .rpt-stat .val{font-size:1.4rem;font-weight:700;color:#0f172a;margin:0;line-height:1.2;}
    .rpt-stat .lbl{font-size:0.75rem;color:#64748b;margin:0.2rem 0 0;}
    .rpt-stat.green .val{color:#16a34a;} .rpt-stat.blue .val{color:#2563eb;}
    .rpt-table-wrap{overflow-x:auto;border-radius:10px;border:1px solid #e2e8f0;}
    table.rpt{width:100%;border-collapse:collapse;font-size:0.84rem;}
    table.rpt thead tr{background:#f1f5f9;}
    table.rpt th{padding:0.65rem 0.85rem;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;color:#475569;white-space:nowrap;text-align:left;}
    table.rpt td{padding:0.6rem 0.85rem;border-top:1px solid #f1f5f9;color:#334155;}
    table.rpt tbody tr:hover{background:#f8fafc;}
    .method-badges{display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1.25rem;}
    .method-badge{background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;padding:0.4rem 0.85rem;font-size:0.8rem;}
    .method-badge strong{color:#0f172a;}
    .empty-state{text-align:center;padding:3rem;color:#94a3b8;font-size:0.9rem;}
</style>

<div class="rpt-summary">
    <div class="rpt-stat blue"><p class="val">{{ $this->totalRepayments }}</p><p class="lbl">Total Transactions</p></div>
    <div class="rpt-stat green"><p class="val">KES {{ number_format($this->totalCollected,0) }}</p><p class="lbl">Total Collected</p></div>
</div>

@if(count($this->methodBreakdown) > 0)
<div class="method-badges">
    @foreach($this->methodBreakdown as $method => $total)
    <div class="method-badge">{{ ucfirst(str_replace('_',' ',$method)) }}: <strong>KES {{ number_format($total,2) }}</strong></div>
    @endforeach
</div>
@endif

@include('filament.pages.reports._filter-bar', ['fields' => [
    ['type'=>'text',   'wire'=>'search',         'label'=>'Search',        'placeholder'=>'Member / Ref / Loan #'],
    ['type'=>'select', 'wire'=>'group_id',        'label'=>'Group',         'placeholder'=>'All Groups',
     'options'=> collect($groups)->pluck('name','id')->toArray()],
    ['type'=>'select', 'wire'=>'payment_method',  'label'=>'Payment Method','placeholder'=>'All Methods',
     'options'=>['group_payment'=>'Group Payment','cash'=>'Cash','mpesa'=>'M-Pesa','bank'=>'Bank','cheque'=>'Cheque']],
    ['type'=>'date',   'wire'=>'date_from',       'label'=>'Date From'],
    ['type'=>'date',   'wire'=>'date_to',         'label'=>'Date To'],
]])

<div class="rpt-table-wrap">
    @if(count($repayments) > 0)
    <table class="rpt">
        <thead>
            <tr>
                <th>#</th><th>Date</th><th>Ref #</th><th>Loan #</th>
                <th>Member</th><th>ID No.</th><th>Group</th><th>Method</th>
                <th style="text-align:right">Amount (KES)</th>
                <th style="text-align:right">New Balance (KES)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($repayments as $i => $r)
            <tr>
                <td style="color:#94a3b8">{{ $i+1 }}</td>
                <td>{{ $r['date'] }}</td>
                <td style="font-family:monospace;font-size:0.78rem">{{ $r['ref'] }}</td>
                <td style="font-family:monospace">{{ $r['loan_no'] }}</td>
                <td>{{ $r['member'] }}</td>
                <td style="font-family:monospace">{{ $r['id_number'] }}</td>
                <td>{{ $r['group'] }}</td>
                <td>{{ ucfirst(str_replace('_',' ',$r['method'])) }}</td>
                <td style="text-align:right;font-weight:600;color:#16a34a">{{ number_format($r['amount'],2) }}</td>
                <td style="text-align:right">{{ number_format($r['balance'],2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#f1f5f9;font-weight:700;">
                <td colspan="8" style="padding:0.65rem 0.85rem;">TOTALS ({{ count($repayments) }} transactions)</td>
                <td style="text-align:right;padding:0.65rem 0.85rem;color:#16a34a">{{ number_format($this->totalCollected,2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @else
    <div class="empty-state">No repayments found for the selected period.</div>
    @endif
</div>
</x-filament-panels::page>