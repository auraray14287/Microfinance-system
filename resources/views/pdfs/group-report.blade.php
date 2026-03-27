<!DOCTYPE html>
<html>
<head>
    <title>Group Report - {{ $group->name }}</title>
    <style>
        @page { margin: 15mm; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            font-weight: bold;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        /* ── TITLE ── */
        .report-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 14px 0 2px 0;
        }
        .report-subtitle {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-bottom: 4px;
        }
        .report-period {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 12px;
            color: #2d5016;
        }

        /* ── SECTIONS ── */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 18px 0 8px 0;
            padding: 5px 8px;
            background-color: #2d5016;
            color: #fff;
        }
        .subsection-title {
            font-size: 11px;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0 6px 0;
            color: #2d5016;
        }

        /* ── FIELDS ── */
        .field-row { display: table; width: 100%; margin-bottom: 5px; }
        .field-label { display: table-cell; width: 35%; font-weight: bold; }
        .field-value { display: table-cell; width: 65%; border-bottom: 1px solid #999; padding-bottom: 1px; }

        /* ── TABLES ── */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 14px 0;
            font-size: 10px;
        }
        table.data-table th {
            background-color: #2d5016;
            color: #fff;
            padding: 5px 7px;
            text-align: left;
            font-weight: bold;
        }
        table.data-table td {
            padding: 4px 7px;
            border-bottom: 1px solid #ddd;
        }
        table.data-table tr:nth-child(even) td { background-color: #f5f8f2; }
        table.data-table tfoot td {
            font-weight: bold;
            background-color: #e8f0e2;
            border-top: 2px solid #2d5016;
        }
        table.data-table .text-right { text-align: right; }
        table.data-table .text-center { text-align: center; }

        /* ── SUMMARY BOXES ── */
        .summary-grid { display: table; width: 100%; margin: 8px 0 14px 0; }
        .summary-box {
            display: table-cell;
            width: 25%;
            padding: 8px 10px;
            border: 1px solid #2d5016;
            text-align: center;
            vertical-align: middle;
        }
        .summary-box .summary-label { font-size: 9px; color: #555; display: block; }
        .summary-box .summary-value { font-size: 14px; font-weight: bold; color: #2d5016; display: block; }

        /* ── PERFORMANCE BADGE ── */
        .badge-good { color: #fff; background-color: #2d5016; padding: 2px 8px; border-radius: 3px; }
        .badge-warn { color: #fff; background-color: #8b6914; padding: 2px 8px; border-radius: 3px; }
        .badge-bad  { color: #fff; background-color: #8b0000; padding: 2px 8px; border-radius: 3px; }

        /* ── MISC ── */
        .watermark {
            position: fixed; bottom: 50px; right: 50px;
            font-size: 120px; color: rgba(0,0,0,0.04);
            z-index: -1; font-weight: bold;
        }
        .page-break { page-break-before: always; }
        .divider { border-top: 1px solid #ccc; margin: 12px 0; }
        .generated-note { font-size: 9px; color: #888; text-align: right; margin-top: 20px; }
    </style>
</head>
<body>

<div class="watermark">RBM</div>

{{-- ═══════════ HEADER ═══════════ --}}
@php
    $logoPath = null;
    $hardcoded = public_path('images/rafikibora-logo.png');
    if (file_exists($hardcoded))                          { $logoPath = $hardcoded; }
    elseif (file_exists(public_path('companyLogo.png')))  { $logoPath = public_path('companyLogo.png'); }
    elseif (file_exists(public_path('logo.png')))         { $logoPath = public_path('logo.png'); }
    elseif (file_exists(public_path('logo.jpg')))         { $logoPath = public_path('logo.jpg'); }

    $logoBase64 = null;
    if ($logoPath && file_exists($logoPath)) {
        $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        $mime = $ext === 'jpg' || $ext === 'jpeg' ? 'image/jpeg' : 'image/png';
        $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
    }

    $displayCompany = $user->organization->name
                   ?? $user->company_name
                   ?? $user->organisation_name
                   ?? 'RAFIKI BORA MICROFINANCE';
    $emailDisplay = $user->company_representative_email ?? ('info' . '@' . 'rafikibora.co.ke');
@endphp

<div style="text-align:center; margin-bottom: 14px;">
    @if($logoBase64)
        <img src="{{ $logoBase64 }}" alt="Logo" style="width:200px; height:100px; object-fit:contain; display:block; margin: 0 auto;">
    @else
        <div style="width:90px;height:90px;border:2px solid #8b6914;border-radius:45px;display:inline-block;line-height:90px;font-weight:bold;color:#2d5016;font-size:22px;">RBM</div>
    @endif
</div>

{{-- ═══════════ REPORT TITLE ═══════════ --}}
<div class="report-title">Group Comprehensive Report</div>
<div class="report-subtitle">{{ strtoupper($group->name) }}</div>
@if($dateFrom || $dateTo)
<div class="report-period">
    Period: {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d M Y') : 'All time' }}
    &nbsp;—&nbsp;
    {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d M Y') : \Carbon\Carbon::now()->format('d M Y') }}
</div>
@endif

{{-- ═══════════ SECTION 1: GROUP PROFILE ═══════════ --}}
@php
    $officer      = $group->officer;
    $chairperson  = $group->chairperson ? \App\Models\Member::find($group->chairperson) : null;
    $secretary    = $group->secretary   ? \App\Models\Member::find($group->secretary)   : null;
    $treasurer    = $group->treasurer   ? \App\Models\Member::find($group->treasurer)   : null;
@endphp

<div class="section-title">Section 1 — Group Profile</div>

<div class="field-row">
    <span class="field-label">Group Name:</span>
    <span class="field-value">{{ strtoupper($group->name) }}</span>
</div>
<div class="field-row">
    <span class="field-label">Registration Number:</span>
    <span class="field-value">{{ $group->registration_number ?? '—' }}</span>
</div>
<div class="field-row">
    <span class="field-label">Physical Location:</span>
    <span class="field-value">{{ $group->location ?? '—' }}</span>
</div>
<div class="field-row">
    <span class="field-label">Contact:</span>
    <span class="field-value">{{ $group->contact ?? '—' }}</span>
</div>
<div class="field-row">
    <span class="field-label">Assigned Officer:</span>
    <span class="field-value">{{ $officer->name ?? '—' }}</span>
</div>
<div class="field-row">
    <span class="field-label">Status:</span>
    <span class="field-value">{{ ucfirst($group->status) }}</span>
</div>
<div class="field-row">
    <span class="field-label">Date Registered:</span>
    <span class="field-value">{{ $group->created_at ? $group->created_at->format('d M Y') : '—' }}</span>
</div>

<div class="subsection-title">Leadership</div>
<div class="field-row">
    <span class="field-label">Chairperson:</span>
    <span class="field-value">{{ $chairperson ? strtoupper($chairperson->full_name) : '—' }}</span>
</div>
<div class="field-row">
    <span class="field-label">Secretary:</span>
    <span class="field-value">{{ $secretary ? strtoupper($secretary->full_name) : '—' }}</span>
</div>
<div class="field-row">
    <span class="field-label">Treasurer:</span>
    <span class="field-value">{{ $treasurer ? strtoupper($treasurer->full_name) : '—' }}</span>
</div>

{{-- ═══════════ SECTION 2: MEMBERS ═══════════ --}}
@php
    $members = $group->members()->get();
    $totalMembers = $members->count();
    $activeMembers = $members->where('status', 'active')->count();
@endphp

<div class="section-title">Section 2 — Members ({{ $totalMembers }})</div>

<div class="summary-grid">
    <div class="summary-box">
        <span class="summary-label">Total Members</span>
        <span class="summary-value">{{ $totalMembers }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Active Members</span>
        <span class="summary-value">{{ $activeMembers }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Male</span>
        <span class="summary-value">{{ $members->where('gender','male')->count() + $members->where('gender','Male')->count() }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Female</span>
        <span class="summary-value">{{ $members->where('gender','female')->count() + $members->where('gender','Female')->count() }}</span>
    </div>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>ID Number</th>
            <th>Mobile</th>
            <th>Gender</th>
            <th>Business</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($members as $i => $member)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ strtoupper($member->full_name) }}</td>
            <td>{{ $member->id_number ?? '—' }}</td>
            <td>{{ $member->mobile_no ?? $member->phone ?? '—' }}</td>
            <td>{{ ucfirst($member->gender ?? '—') }}</td>
            <td>{{ $member->business_name ?? '—' }}</td>
            <td>{{ ucfirst($member->status ?? '—') }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">No members found</td></tr>
        @endforelse
    </tbody>
</table>

{{-- ═══════════ SECTION 3: FINANCIAL STATEMENT — SAVINGS ═══════════ --}}
@php
    $savingsQuery = \App\Models\Saving::where('group_id', $group->id);
    if ($dateFrom) $savingsQuery->where('contribution_date', '>=', $dateFrom);
    if ($dateTo)   $savingsQuery->where('contribution_date', '<=', $dateTo);
    $savings = $savingsQuery->with('member')->orderBy('contribution_date', 'desc')->get();

    $totalSavings   = $savings->sum('amount');
    $avgSavings     = $totalMembers > 0 ? $totalSavings / $totalMembers : 0;
    $savingsByMember = $savings->groupBy('member_id');
@endphp

<div class="section-title">Section 3 — Financial Statement: Savings</div>

<div class="summary-grid">
    <div class="summary-box">
        <span class="summary-label">Total Savings (KES)</span>
        <span class="summary-value">{{ number_format($totalSavings, 2) }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Avg. Savings / Member (KES)</span>
        <span class="summary-value">{{ number_format($avgSavings, 2) }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Total Transactions</span>
        <span class="summary-value">{{ $savings->count() }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Contributing Members</span>
        <span class="summary-value">{{ $savingsByMember->count() }}</span>
    </div>
</div>

<div class="subsection-title">Savings by Member</div>
<table class="data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Member Name</th>
            <th class="text-right">Total Saved (KES)</th>
            <th class="text-right">Transactions</th>
            <th>Last Contribution</th>
        </tr>
    </thead>
    <tbody>
        @php $sRow = 1; @endphp
        @foreach($members as $member)
        @php
            $memberSavings = $savingsByMember->get($member->id, collect());
            $memberTotal   = $memberSavings->sum('amount');
            $lastDate      = $memberSavings->sortByDesc('contribution_date')->first()?->contribution_date;
        @endphp
        <tr>
            <td>{{ $sRow++ }}</td>
            <td>{{ strtoupper($member->full_name) }}</td>
            <td class="text-right">{{ number_format($memberTotal, 2) }}</td>
            <td class="text-center">{{ $memberSavings->count() }}</td>
            <td>{{ $lastDate ? \Carbon\Carbon::parse($lastDate)->format('d/m/Y') : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">TOTAL</td>
            <td class="text-right">KES {{ number_format($totalSavings, 2) }}</td>
            <td class="text-center">{{ $savings->count() }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

<div class="subsection-title">Savings Transaction Log</div>
<table class="data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Member</th>
            <th class="text-right">Amount (KES)</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @forelse($savings as $i => $saving)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $saving->contribution_date ? \Carbon\Carbon::parse($saving->contribution_date)->format('d/m/Y') : '—' }}</td>
            <td>{{ $saving->member ? strtoupper($saving->member->full_name) : '—' }}</td>
            <td class="text-right">{{ number_format($saving->amount, 2) }}</td>
            <td>{{ $saving->notes ?? '' }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No savings records in this period</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">TOTAL</td>
            <td class="text-right">KES {{ number_format($totalSavings, 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

{{-- ═══════════ SECTION 4: FINANCIAL STATEMENT — LOANS ═══════════ --}}
@php
    $memberIds = $members->pluck('id');

    $loansQuery = \App\Models\Loan::whereIn('member_id', $memberIds);
    if ($dateFrom) $loansQuery->where('loan_release_date', '>=', $dateFrom);
    if ($dateTo)   $loansQuery->where('loan_release_date', '<=', $dateTo);
    $loans = $loansQuery->with(['member', 'repayments'])->get();

    $totalLoansCount    = $loans->count();
    $totalDisbursed     = $loans->sum('principal_amount');
    $totalRepayable     = $loans->sum('repayment_amount');
    $totalRepaid        = $loans->sum(fn($l) => $l->repayments->sum('payments'));
    $totalOutstanding   = max(0, $totalRepayable - $totalRepaid);

    // Loan status breakdown
    $approvedLoans  = $loans->whereIn('loan_status', ['approved','partially_paid','fully_paid'])->count();
    $fullyPaid      = $loans->where('loan_status', 'fully_paid')->count();
    $partiallyPaid  = $loans->where('loan_status', 'partially_paid')->count();
    $defaulted      = $loans->where('loan_status', 'defaulted')->count();
    $requested      = $loans->where('loan_status', 'requested')->count();
@endphp

<div class="section-title">Section 4 — Financial Statement: Loans</div>

<div class="summary-grid">
    <div class="summary-box">
        <span class="summary-label">Total Loans</span>
        <span class="summary-value">{{ $totalLoansCount }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Total Disbursed (KES)</span>
        <span class="summary-value">{{ number_format($totalDisbursed, 2) }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Total Repaid (KES)</span>
        <span class="summary-value">{{ number_format($totalRepaid, 2) }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Outstanding (KES)</span>
        <span class="summary-value">{{ number_format($totalOutstanding, 2) }}</span>
    </div>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Loan ID</th>
            <th>Member</th>
            <th>Product / Type</th>
            <th class="text-right">Principal (KES)</th>
            <th class="text-right">Repayable (KES)</th>
            <th class="text-right">Repaid (KES)</th>
            <th class="text-right">Balance (KES)</th>
            <th>Release Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($loans as $loan)
        @php
            $repaid   = $loan->repayments->sum('payments');
            $balance  = max(0, ($loan->repayment_amount ?? 0) - $repaid);
            $loanTypeName = $loan->loan_type()->first()->loan_name ?? 'Normal loan';
            $productName  = $loan->product->name ?? null;
            $typeDisplay  = $productName ? "$productName ($loanTypeName)" : $loanTypeName;
        @endphp
        <tr>
            <td>{{ $loan->loan_number ?? $loan->id }}</td>
            <td>{{ $loan->member ? strtoupper($loan->member->full_name) : '—' }}</td>
            <td>{{ $typeDisplay }}</td>
            <td class="text-right">{{ number_format($loan->principal_amount ?? 0, 2) }}</td>
            <td class="text-right">{{ number_format($loan->repayment_amount ?? 0, 2) }}</td>
            <td class="text-right">{{ number_format($repaid, 2) }}</td>
            <td class="text-right">{{ number_format($balance, 2) }}</td>
            <td>{{ $loan->loan_release_date ? \Carbon\Carbon::parse($loan->loan_release_date)->format('d/m/Y') : '—' }}</td>
            <td>{{ ucfirst(str_replace('_',' ', $loan->loan_status ?? '')) }}</td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-center">No loans in this period</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">TOTAL</td>
            <td class="text-right">{{ number_format($totalDisbursed, 2) }}</td>
            <td class="text-right">{{ number_format($totalRepayable, 2) }}</td>
            <td class="text-right">{{ number_format($totalRepaid, 2) }}</td>
            <td class="text-right">{{ number_format($totalOutstanding, 2) }}</td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
</table>

{{-- ═══════════ SECTION 5: LOAN PERFORMANCE & DEFAULT RATE ═══════════ --}}
@php
    // Default rate = loans defaulted or overdue / total active loans
    $activeLoanCount = $loans->whereNotIn('loan_status', ['requested','denied'])->count();

    // Overdue: partially_paid loans past clearance date
    $overdueCount = $loans->filter(function($l) {
        if (!in_array($l->loan_status, ['partially_paid','approved'])) return false;
        if (!$l->clearance_date) return false;
        return \Carbon\Carbon::parse($l->clearance_date)->isPast();
    })->count();

    $badLoans       = $defaulted + $overdueCount;
    $defaultRate    = $activeLoanCount > 0 ? ($badLoans / $activeLoanCount) * 100 : 0;
    $repaymentRate  = $totalRepayable > 0 ? ($totalRepaid / $totalRepayable) * 100 : 0;
    $onTimeLoans    = $loans->where('loan_status', 'fully_paid')->count();
    $onTimeRate     = $activeLoanCount > 0 ? ($onTimeLoans / max($activeLoanCount,1)) * 100 : 0;

    // Performance rating
    if ($defaultRate <= 5)       { $perfLabel = 'EXCELLENT'; $perfClass = 'badge-good'; }
    elseif ($defaultRate <= 15)  { $perfLabel = 'GOOD';      $perfClass = 'badge-good'; }
    elseif ($defaultRate <= 30)  { $perfLabel = 'FAIR';      $perfClass = 'badge-warn'; }
    else                         { $perfLabel = 'POOR';      $perfClass = 'badge-bad';  }
@endphp

<div class="section-title">Section 5 — Loan Performance & Default Rate</div>

<div class="summary-grid">
    <div class="summary-box">
        <span class="summary-label">Default Rate</span>
        <span class="summary-value" style="color:{{ $defaultRate > 15 ? '#8b0000' : '#2d5016' }}">
            {{ number_format($defaultRate, 1) }}%
        </span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Repayment Rate</span>
        <span class="summary-value">{{ number_format($repaymentRate, 1) }}%</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Fully Paid Loans</span>
        <span class="summary-value">{{ $fullyPaid }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Defaulted / Overdue</span>
        <span class="summary-value" style="color:#8b0000">{{ $badLoans }}</span>
    </div>
</div>

<div class="field-row" style="margin-bottom:6px;">
    <span class="field-label">Overall Performance Rating:</span>
    <span class="field-value"><span class="{{ $perfClass }}">{{ $perfLabel }}</span></span>
</div>
<div class="field-row" style="margin-bottom:6px;">
    <span class="field-label">Fully Paid Loans:</span>
    <span class="field-value">{{ $fullyPaid }} of {{ $totalLoansCount }}</span>
</div>
<div class="field-row" style="margin-bottom:6px;">
    <span class="field-label">Partially Paid:</span>
    <span class="field-value">{{ $partiallyPaid }}</span>
</div>
<div class="field-row" style="margin-bottom:6px;">
    <span class="field-label">Defaulted:</span>
    <span class="field-value">{{ $defaulted }}</span>
</div>
<div class="field-row" style="margin-bottom:6px;">
    <span class="field-label">Overdue (past clearance date):</span>
    <span class="field-value">{{ $overdueCount }}</span>
</div>
<div class="field-row" style="margin-bottom:6px;">
    <span class="field-label">Pending / Requested:</span>
    <span class="field-value">{{ $requested }}</span>
</div>

{{-- Per-member loan performance --}}
<div class="subsection-title">Per-Member Loan Performance</div>
<table class="data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Member</th>
            <th class="text-center">Loans</th>
            <th class="text-right">Total Borrowed (KES)</th>
            <th class="text-right">Total Repaid (KES)</th>
            <th class="text-right">Outstanding (KES)</th>
            <th class="text-center">Fully Paid</th>
            <th class="text-center">Defaulted</th>
            <th class="text-center">Performance</th>
        </tr>
    </thead>
    <tbody>
        @php $mRow = 1; @endphp
        @foreach($members as $member)
        @php
            $mLoans      = $loans->where('member_id', $member->id);
            $mBorrowed   = $mLoans->sum('principal_amount');
            $mRepayable  = $mLoans->sum('repayment_amount');
            $mRepaid     = $mLoans->sum(fn($l) => $l->repayments->sum('payments'));
            $mOutstanding= max(0, $mRepayable - $mRepaid);
            $mFullyPaid  = $mLoans->where('loan_status','fully_paid')->count();
            $mDefaulted  = $mLoans->where('loan_status','defaulted')->count();
            $mOverdue    = $mLoans->filter(function($l) {
                if (!in_array($l->loan_status, ['partially_paid','approved'])) return false;
                return $l->clearance_date && \Carbon\Carbon::parse($l->clearance_date)->isPast();
            })->count();
            $mBad = $mDefaulted + $mOverdue;
            $mTotal = $mLoans->whereNotIn('loan_status',['requested','denied'])->count();
            $mDefRate = $mTotal > 0 ? ($mBad / $mTotal) * 100 : 0;
            if ($mDefRate <= 5)      { $mPerf = 'Good';    $mStyle = 'color:#2d5016'; }
            elseif ($mDefRate <= 30) { $mPerf = 'Fair';    $mStyle = 'color:#8b6914'; }
            else                     { $mPerf = 'Poor';    $mStyle = 'color:#8b0000'; }
            if ($mLoans->count() === 0) { $mPerf = '—'; $mStyle = ''; }
        @endphp
        <tr>
            <td>{{ $mRow++ }}</td>
            <td>{{ strtoupper($member->full_name) }}</td>
            <td class="text-center">{{ $mLoans->count() }}</td>
            <td class="text-right">{{ number_format($mBorrowed, 2) }}</td>
            <td class="text-right">{{ number_format($mRepaid, 2) }}</td>
            <td class="text-right">{{ number_format($mOutstanding, 2) }}</td>
            <td class="text-center">{{ $mFullyPaid }}</td>
            <td class="text-center">{{ $mDefaulted }}</td>
            <td class="text-center" style="{{ $mStyle }}">{{ $mPerf }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- ═══════════ SECTION 6: LOAN INSTALLMENT PAYMENTS ═══════════ --}}
@php
    $repaymentsQuery = \App\Models\Repayments::whereIn('loan_id', $loans->pluck('id'));
    if ($dateFrom) $repaymentsQuery->where('created_at', '>=', $dateFrom);
    if ($dateTo)   $repaymentsQuery->where('created_at', '<=', $dateTo . ' 23:59:59');
    $repayments = $repaymentsQuery->with(['loan.member'])->orderBy('created_at','desc')->get();
    $totalInstallmentsPaid = $repayments->sum('payments');
@endphp

<div class="section-title">Section 6 — Loan Installment Payments</div>

<div class="summary-grid">
    <div class="summary-box">
        <span class="summary-label">Total Transactions</span>
        <span class="summary-value">{{ $repayments->count() }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Total Collected (KES)</span>
        <span class="summary-value">{{ number_format($totalInstallmentsPaid, 2) }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Total Repayable (KES)</span>
        <span class="summary-value">{{ number_format($totalRepayable, 2) }}</span>
    </div>
    <div class="summary-box">
        <span class="summary-label">Collection Rate</span>
        <span class="summary-value">{{ $totalRepayable > 0 ? number_format(($totalInstallmentsPaid / $totalRepayable) * 100, 1) : '0' }}%</span>
    </div>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Member</th>
            <th>Loan ID</th>
            <th class="text-right">Amount Paid (KES)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($repayments as $i => $repayment)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $repayment->created_at ? $repayment->created_at->format('d/m/Y') : '—' }}</td>
            <td>{{ $repayment->loan?->member ? strtoupper($repayment->loan->member->full_name) : '—' }}</td>
            <td>{{ $repayment->loan?->loan_number ?? $repayment->loan_id }}</td>
            <td class="text-right">{{ number_format($repayment->payments ?? 0, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No installment payments in this period</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">TOTAL COLLECTED</td>
            <td class="text-right">KES {{ number_format($totalInstallmentsPaid, 2) }}</td>
        </tr>
    </tfoot>
</table>

{{-- ═══════════ GENERATED NOTE ═══════════ --}}
<div class="divider"></div>
<div class="generated-note">
    Report generated by {{ auth()->user()->name ?? 'System' }} on {{ \Carbon\Carbon::now()->format('d M Y, H:i') }} |
    {{ strtoupper($displayCompany) }}
</div>

</body>
</html>