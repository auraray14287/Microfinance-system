<x-filament-panels::page>
<style>
    .rpt-summary{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;margin-bottom:1.5rem;}
    .rpt-stat{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:1rem 1.25rem;}
    .rpt-stat .val{font-size:1.4rem;font-weight:700;color:#0f172a;margin:0;line-height:1.2;}
    .rpt-stat .lbl{font-size:0.75rem;color:#64748b;margin:0.2rem 0 0;}
    .rpt-stat.red .val{color:#dc2626;} .rpt-stat.amber .val{color:#b45309;} .rpt-stat.blue .val{color:#2563eb;}
    .rpt-table-wrap{overflow-x:auto;border-radius:10px;border:1px solid #e2e8f0;}
    table.rpt{width:100%;border-collapse:collapse;font-size:0.84rem;}
    table.rpt thead tr{background:#fef2f2;}
    table.rpt th{padding:0.65rem 0.85rem;font-weight:700;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;color:#475569;white-space:nowrap;text-align:left;}
    table.rpt td{padding:0.6rem 0.85rem;border-top:1px solid #f1f5f9;color:#334155;}
    table.rpt tbody tr:hover{background:#fef2f2;}
    .badge{display:inline-block;padding:0.18rem 0.55rem;border-radius:99px;font-size:0.72rem;font-weight:600;}
    .badge-defaulted{background:#fee2e2;color:#dc2626;}
    .badge-overdue{background:#fef9c3;color:#a16207;}
    .badge-risk{background:#f3e8ff;color:#7e22ce;}
    .overdue-days{font-weight:700;color:#dc2626;}
    .empty-state{text-align:center;padding:3rem;color:#94a3b8;font-size:0.9rem;}
</style>

<div class="rpt-summary">
    <div class="rpt-stat blue"><p class="val">{{ $this->totalDefaulters }}</p><p class="lbl">Total Records</p></div>
    <div class="rpt-stat red"><p class="val">{{ $this->defaultedCount }}</p><p class="lbl">Defaulted</p></div>
    <div class="rpt-stat amber"><p class="val">{{ $this->overdueCount }}</p><p class="lbl">Overdue</p></div>
    <div class="rpt-stat red"><p class="val">KES {{ number_format($this->totalAtRisk,0) }}</p><p class="lbl">Total At Risk</p></div>
</div>

@include('filament.pages.reports._filter-bar', ['fields' => [
    ['type'=>'text',   'wire'=>'search',       'label'=>'Search',       'placeholder'=>'Member name / ID'],
    ['type'=>'select', 'wire'=>'group_id',     'label'=>'Group',        'placeholder'=>'All Groups',
     'options'=> collect($groups)->pluck('name','id')->toArray()],
    ['type'=>'select', 'wire'=>'overdue_type', 'label'=>'Type',         'placeholder'=>'All',
     'options'=>['defaulted'=>'Defaulted','overdue'=>'Overdue']],
    ['type'=>'date',   'wire'=>'date_from',    'label'=>'Date From'],
    ['type'=>'date',   'wire'=>'date_to',      'label'=>'Date To'],
]])

<div class="rpt-table-wrap">
    @if(count($defaulters) > 0)
    <table class="rpt">
        <thead>
            <tr>
                <th>#</th><th>Type</th><th>Loan #</th><th>Member</th><th>ID No.</th>
                <th>Mobile</th><th>Group</th><th>Due Date</th>
                <th style="text-align:right">Days Overdue</th>
                <th style="text-align:right">Principal (KES)</th>
                <th style="text-align:right">Balance (KES)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($defaulters as $i => $d)
            <tr>
                <td style="color:#94a3b8">{{ $i+1 }}</td>
                <td>
                    @if($d['type']==='Defaulted') <span class="badge badge-defaulted">Defaulted</span>
                    @elseif($d['type']==='Overdue') <span class="badge badge-overdue">Overdue</span>
                    @else <span class="badge badge-risk">At Risk</span>
                    @endif
                </td>
                <td style="font-family:monospace">{{ $d['loan_number'] }}</td>
                <td style="font-weight:600">{{ $d['member'] }}</td>
                <td style="font-family:monospace">{{ $d['id_number'] }}</td>
                <td>{{ $d['mobile'] }}</td>
                <td>{{ $d['group'] }}</td>
                <td>{{ $d['due_date'] }}</td>
                <td style="text-align:right">
                    @if($d['days_overdue'] > 0)
                        <span class="overdue-days">{{ $d['days_overdue'] }} days</span>
                    @else —
                    @endif
                </td>
                <td style="text-align:right">{{ number_format($d['principal'],2) }}</td>
                <td style="text-align:right;font-weight:700;color:#dc2626">{{ number_format($d['balance'],2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:#fef2f2;font-weight:700;">
                <td colspan="9" style="padding:0.65rem 0.85rem;">TOTALS AT RISK</td>
                <td style="text-align:right;padding:0.65rem 0.85rem;">{{ number_format(collect($defaulters)->sum('principal'),2) }}</td>
                <td style="text-align:right;padding:0.65rem 0.85rem;color:#dc2626">{{ number_format($this->totalAtRisk,2) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
    <div class="empty-state">✓ No defaulters or overdue loans found for the selected filters.</div>
    @endif
</div>
</x-filament-panels::page>