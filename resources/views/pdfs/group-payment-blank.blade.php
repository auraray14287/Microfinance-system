<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Group Payment Form – {{ $group->name }}</title>
<style>
@page { margin:12mm 15mm; size:A4 landscape; }
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif; font-size:9px; color:#1a1a1a; background:#fff; padding:8px 10px; }
table { border-collapse:collapse; }
.w100 { width:100%; }
.header-bottom { border-bottom:3px solid #1d4ed8; margin-bottom:5px; }
.company-name { font-size:17px; font-weight:bold; color:#2d5016; }
.slogan { font-style:italic; font-size:9px; color:#8b6914; font-weight:bold; }
.line-g { height:2px; background:#2d5016; margin:2px 0; }
.line-go { height:2px; background:#8b6914; margin:1px 0; }
.contact { font-size:8px; color:#555; line-height:1.7; text-align:right; }
.form-title { text-align:center; font-size:13px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; padding:5px 0 4px; }
.gp-table { width:100%; background:#eff6ff; border:1px solid #bfdbfe; margin-bottom:5px; }
.gp-table td { padding:4px 8px; border-right:1px solid #dbeafe; }
.gp-table td:last-child { border-right:none; }
.lbl { font-size:7px; text-transform:uppercase; letter-spacing:0.4px; color:#6b7280; display:block; }
.val { font-size:10px; font-weight:bold; color:#1e3a5f; }
.sum-outer { width:100%; margin-bottom:5px; }
.sum-outer td { width:25%; padding:2px 3px; vertical-align:top; }
.sum-box { border:1px solid #e5e7eb; padding:5px 8px; }
.sum-box.ov { border-color:#fca5a5; background:#fff5f5; }
.sum-lbl { font-size:7px; text-transform:uppercase; color:#6b7280; }
.sum-val { font-size:11px; font-weight:bold; color:#1e3a5f; margin-top:2px; }
.sum-val.red { color:#dc2626; }
.sum-sub { font-size:7px; color:#9ca3af; }
/* MAIN TABLE */
.main { width:100%; }
.main th { background:#1d4ed8; color:#fff; font-size:8px; text-transform:uppercase; padding:5px 7px; text-align:left; }
.main th.ov-th { background:#dc2626; }
.main td { padding:4px 7px; border-bottom:1px solid #f0f0f0; font-size:9px; vertical-align:middle; }
.main tr.even td { background:#f9fafb; }
.main tr.even td.ov-c { background:#ffe4e4; }
.main td.ov-c { background:#fff0f0; color:#dc2626; font-weight:bold; text-align:center; }
.main td.blank-c { border-bottom:1px solid #bbb; }
.main tfoot td { background:#eef2ff; font-weight:bold; border-top:2px solid #1d4ed8; padding:5px 7px; font-size:9px; }
.main tfoot td.ov-c { background:#fff0f0; color:#dc2626; text-align:center; }
.sig-table { width:100%; margin-top:8px; }
.sig-table td { text-align:center; border-top:1px solid #374151; padding-top:3px; font-size:8px; color:#6b7280; width:33%; }
.footer { border-top:1px solid #e5e7eb; padding-top:3px; font-size:7px; color:#9ca3af; margin-top:6px; }
</style>
</head>
<body>
@php
    $user2 = auth()->user();
    $logoPath2 = null;
    foreach (['images/rafikibora-logo.png','companyLogo.png','logo.png','logo.jpg'] as $f) {
        if (file_exists(public_path($f))) { $logoPath2 = public_path($f); break; }
    }
    $displayCompany2 = $user2->organization->name ?? $user2->company_name ?? 'RAFIKI BORA MICROFINANCE';
    $totalOverdue = collect($rows)->sum(fn($r) => $r['overdue'] ?? 0);
    $totalExpected = collect($rows)->sum('expected_loan');
@endphp

{{-- HEADER --}}
<table class="w100 header-bottom">
<tr>
    <td style="width:120px;vertical-align:middle;padding:8px 12px 10px 0;">
        @if($logoPath2)
            <img src="{{ $logoPath2 }}" style="width:110px;height:80px;object-fit:contain;">
        @else
            <div style="width:90px;height:90px;border:2px solid #8b6914;border-radius:45px;text-align:center;line-height:90px;font-weight:bold;color:#2d5016;font-size:18px;">RBM</div>
        @endif
    </td>
    <td style="vertical-align:middle;padding:6px 20px 8px 10px;width:60%;">
        <div class="company-name">{{ strtoupper($displayCompany2) }}</div>
        <div class="slogan">Empowering Communities Through Financial Inclusion</div>
        <div class="line-g"></div>
        <div class="line-go"></div>
    </td>
    <td style="vertical-align:middle;text-align:right;padding:6px 0 8px 20px;width:220px;" class="contact">
        <strong>Phone:</strong> {{ $user2->company_phone ?? '+254 700 000 000' }}<br>
        <strong>Email:</strong> {{ $user2->company_representative_email ?? 'info@rafikibora.co.ke' }}<br>
        <strong>Location:</strong> {{ $orgAddress }}
    </td>
</tr>
</table>

{{-- TITLE --}}
<div class="form-title">Group Payment Form</div>

{{-- GROUP PROFILE --}}
<table class="gp-table">
<tr>
    <td><span class="lbl">Group Name</span><span class="val">{{ strtoupper($group->name) }}</span></td>
    <td><span class="lbl">Reg. Number</span><span class="val">{{ $group->registration_number ?? '—' }}</span></td>
    <td><span class="lbl">Location</span><span class="val">{{ strtoupper($group->location ?? '—') }}</span></td>
    <td><span class="lbl">Payment Date</span><span class="val">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span></td>
    <td><span class="lbl">Loan Officer</span><span class="val">{{ $officer }}</span></td>
    <td><span class="lbl">Total Members</span><span class="val">{{ count($rows) }}</span></td>
</tr>
</table>

{{-- SUMMARY --}}
<table class="sum-outer">
<tr>
    <td><div class="sum-box"><div class="sum-lbl">Total Savings Collected</div><div class="sum-val">KES ___________________</div><div class="sum-sub">from ___ member(s)</div></div></td>
    <td><div class="sum-box"><div class="sum-lbl">Total Loan Payments Received</div><div class="sum-val">KES ___________________</div><div class="sum-sub">from ___ member(s)</div></div></td>
    <td><div class="sum-box ov"><div class="sum-lbl">Overdue Payments</div><div class="sum-val red">KES {{ number_format($totalOverdue, 2) }}</div><div class="sum-sub">auto-calculated from system</div></div></td>
    <td><div class="sum-box"><div class="sum-lbl">Total Expected Installments</div><div class="sum-val">KES {{ number_format($totalExpected, 2) }}</div><div class="sum-sub">&nbsp;</div></div></td>
</tr>
</table>

{{-- MEMBER TABLE --}}
<table class="main">
<thead>
    <tr>
        <th style="width:18px">#</th>
        <th>Member Name</th>
        <th style="width:80px">ID Number</th>
        <th style="width:130px">Savings (KES)</th>
        <th class="ov-th" style="width:110px;text-align:center">Overdue Payment (KES)</th>
        <th style="width:120px;text-align:right">Expected Installment (KES)</th>
    </tr>
</thead>
<tbody>
    @foreach($rows as $i => $row)
    @php $overdue = $row['overdue'] ?? 0; $even = ($i % 2 == 1) ? 'even' : ''; @endphp
    <tr class="{{ $even }}">
        <td>{{ $i + 1 }}</td>
        <td style="font-weight:600">{{ strtoupper($row['name']) }}</td>
        <td style="font-family:monospace;color:#6b7280;font-size:8px">{{ $row['id_number'] }}</td>
        <td class="blank-c">&nbsp;</td>
        <td class="ov-c">
            @if($overdue > 0)
                {{ number_format($overdue, 2) }}
                @if(($row['overdue_days'] ?? 0) > 0)
                    <br><span style="font-size:7px;color:#b91c1c;">({{ $row['overdue_days'] }} days overdue)</span>
                @endif
            @else
                —
            @endif
        </td>
        <td style="text-align:right;font-weight:600">
            @if($row['has_active_loans']) {{ number_format($row['expected_loan'], 2) }}
            @else <em style="color:#9ca3af;font-size:8px">No active loans</em>
            @endif
        </td>
    </tr>
    @endforeach
</tbody>
<tfoot>
    <tr>
        <td colspan="3" style="text-align:right;font-size:8px;text-transform:uppercase;letter-spacing:0.5px;">Totals</td>
        <td class="blank-c">&nbsp;</td>
        <td class="ov-c">{{ $totalOverdue > 0 ? number_format($totalOverdue, 2) : '—' }}</td>
        <td style="text-align:right">KES {{ number_format($totalExpected, 2) }}</td>
    </tr>
</tfoot>
</table>

{{-- SIGNATURES --}}
<table class="sig-table">
<tr>
    <td>Prepared By (Officer)</td>
    <td>Verified By (Supervisor)</td>
    <td>Date</td>
</tr>
</table>

{{-- FOOTER --}}
<table class="footer w100">
<tr>
    <td>{{ $orgName }} — Group Payment Form — {{ $group->name }}</td>
    <td style="text-align:right">Generated: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</td>
</tr>
</table>

</body>
</html>
