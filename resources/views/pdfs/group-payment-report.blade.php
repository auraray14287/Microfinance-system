<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Payment Report – {{ $group->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            background: #fff;
        }

        /* ── HEADER ──────────────────────────────────── */
        .header {
            padding: 18px 24px 12px;
            border-bottom: 3px solid #1d4ed8;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .org-name {
            font-size: 18px;
            font-weight: 700;
            color: #1d4ed8;
            letter-spacing: -0.3px;
        }
        .org-meta {
            font-size: 9px;
            color: #6b7280;
            margin-top: 2px;
        }
        .report-title {
            text-align: right;
        }
        .report-title h2 {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-title p {
            font-size: 9px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* ── GROUP INFO BAND ─────────────────────────── */
        .group-band {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            margin: 14px 24px 0;
            border-radius: 6px;
            padding: 10px 16px;
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }
        .group-band .item label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            display: block;
        }
        .group-band .item span {
            font-size: 11px;
            font-weight: 600;
            color: #1e3a5f;
        }

        /* ── MAIN TABLE ──────────────────────────────── */
        .table-wrap {
            margin: 14px 24px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        thead tr {
            background: #1d4ed8;
            color: #fff;
        }
        thead th {
            padding: 7px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            letter-spacing: 0.2px;
        }
        thead th.num {
            text-align: right;
        }
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        tbody tr:hover {
            background: #eff6ff;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        td.num {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        td.id-col {
            font-family: monospace;
            font-size: 10px;
            color: #4b5563;
        }
        .loan-breakdown {
            font-size: 8.5px;
            color: #9ca3af;
            margin-top: 2px;
        }
        .no-loan {
            font-size: 9px;
            color: #d1d5db;
            font-style: italic;
        }
        .defaulted { color: #dc2626; font-weight: 600; }
        .partial   { color: #d97706; }
        .approved  { color: #16a34a; }

        /* ── TOTALS ROW ──────────────────────────────── */
        tfoot tr {
            background: #1e3a5f;
            color: #fff;
        }
        tfoot td {
            padding: 8px 8px;
            font-weight: 700;
            font-size: 11px;
            border-top: 2px solid #1d4ed8;
        }
        tfoot td.num {
            text-align: right;
        }

        /* ── SUMMARY CARDS ───────────────────────────── */
        .summary-cards {
            display: flex;
            gap: 12px;
            margin: 16px 24px 0;
            flex-wrap: wrap;
        }
        .card {
            flex: 1;
            min-width: 140px;
            border-radius: 6px;
            padding: 10px 14px;
            border: 1px solid;
        }
        .card.savings {
            background: #f0fdf4;
            border-color: #86efac;
        }
        .card.loan-received {
            background: #eff6ff;
            border-color: #93c5fd;
        }
        .card.loan-expected {
            background: #fffbeb;
            border-color: #fcd34d;
        }
        .card label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            display: block;
            margin-bottom: 4px;
        }
        .card .amount {
            font-size: 15px;
            font-weight: 700;
        }
        .card.savings .amount   { color: #15803d; }
        .card.loan-received .amount { color: #1d4ed8; }
        .card.loan-expected .amount { color: #b45309; }
        .card .sub {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* ── RATIO KEY ───────────────────────────────── */
        .ratio-note {
            margin: 12px 24px 0;
            padding: 8px 12px;
            background: #fefce8;
            border-left: 3px solid #eab308;
            border-radius: 3px;
            font-size: 9px;
            color: #713f12;
            line-height: 1.5;
        }

        /* ── FOOTER ──────────────────────────────────── */
        .footer {
            margin-top: 20px;
            padding: 10px 24px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            font-size: 8.5px;
            color: #9ca3af;
        }
        .sig-line {
            margin: 20px 24px 0;
            display: flex;
            gap: 60px;
        }
        .sig-item {
            flex: 1;
        }
        .sig-item .line {
            border-top: 1px solid #374151;
            margin-bottom: 4px;
            width: 160px;
            margin-top: 24px;
        }
        .sig-item label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    {{-- ── HEADER ────────────────────────────────────────── --}}
    <div class="header">
        <div>
            <div class="org-name">{{ $orgName }}</div>
            <div class="org-meta">{{ $orgAddress }}</div>
        </div>
        <div class="report-title">
            <h2>Group Payment Report</h2>
            <p>Generated: {{ now()->format('d M Y, H:i') }}</p>
            <p>Ref: GPS-{{ $group->id }}-{{ \Carbon\Carbon::parse($date)->format('Ymd') }}</p>
        </div>
    </div>

    {{-- ── GROUP INFO ─────────────────────────────────────── --}}
    <div class="group-band">
        <div class="item">
            <label>Group Name</label>
            <span>{{ $group->name }}</span>
        </div>
        <div class="item">
            <label>Reg. Number</label>
            <span>{{ $group->registration_number ?? '—' }}</span>
        </div>
        <div class="item">
            <label>Location</label>
            <span>{{ $group->location ?? '—' }}</span>
        </div>
        <div class="item">
            <label>Payment Date</label>
            <span>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span>
        </div>
        <div class="item">
            <label>Loan Officer</label>
            <span>{{ $officer }}</span>
        </div>
        <div class="item">
            <label>Total Members</label>
            <span>{{ count($rows) }}</span>
        </div>
    </div>

    {{-- ── SUMMARY CARDS ──────────────────────────────────── --}}
    <div class="summary-cards">
        <div class="card savings">
            <label>Total Savings Collected</label>
            <div class="amount">KES {{ number_format($totalSavings, 2) }}</div>
            <div class="sub">from {{ $savingsCount }} member(s)</div>
        </div>
        <div class="card loan-received">
            <label>Total Loan Payments Received</label>
            <div class="amount">KES {{ number_format($totalLoanPayments, 2) }}</div>
            <div class="sub">from {{ $loanPaymentsCount }} member(s)</div>
        </div>
        <div class="card loan-expected">
            <label>Total Expected Installments</label>
            <div class="amount">KES {{ number_format($totalExpected, 2) }}</div>
            <div class="sub">
                @if($totalExpected > 0)
                    @php $coverage = ($totalLoanPayments / $totalExpected) * 100; @endphp
                    {{ number_format($coverage, 1) }}% collected
                @endif
            </div>
        </div>
    </div>

    {{-- ── MAIN TABLE ─────────────────────────────────────── --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:28px">#</th>
                    <th>Member Name</th>
                    <th>ID Number</th>
                    <th>Group</th>
                    <th class="num">Savings (KES)</th>
                    <th class="num">Loan Payment (KES)</th>
                    <th class="num">Expected Installment (KES)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $i => $row)
                <tr>
                    <td style="color:#9ca3af">{{ $i + 1 }}</td>
                    <td style="font-weight:500">{{ $row['name'] }}</td>
                    <td class="id-col">{{ $row['id_number'] }}</td>
                    <td style="color:#4b5563">{{ $row['group_name'] }}</td>

                    {{-- Savings --}}
                    <td class="num" style="color:#15803d">
                        @if($row['savings_input'] > 0)
                            {{ number_format($row['savings_input'], 2) }}
                        @else
                            <span style="color:#d1d5db">—</span>
                        @endif
                    </td>

                    {{-- Loan payment + ratio breakdown --}}
                    <td class="num">
                        @if($row['has_active_loans'])
                            @if($row['loan_input'] > 0)
                                <span style="color:#1d4ed8;font-weight:600">
                                    {{ number_format($row['loan_input'], 2) }}
                                </span>
                                @if(count($row['loan_details']) > 1 && $row['expected_loan'] > 0)
                                @php
                                    $expectedTotal = $row['expected_loan'];
                                    $paid = (float)$row['loan_input'];
                                @endphp
                                <div class="loan-breakdown">
                                    @foreach($row['loan_details'] as $ld)
                                    @php
                                        $ratio = $ld['amount_per_installment'] / $expectedTotal;
                                        $share = round($paid * $ratio, 2);
                                    @endphp
                                    <div>{{ $ld['loan_number'] }}: KES {{ number_format($share, 2) }}</div>
                                    @endforeach
                                </div>
                                @endif
                            @else
                                <span class="defaulted">0.00 ⚠</span>
                            @endif
                        @else
                            <span class="no-loan">No active loans</span>
                        @endif
                    </td>

                    {{-- Expected installment --}}
                    <td class="num">
                        @if($row['has_active_loans'])
                            <span style="color:#b45309;font-weight:600">
                                {{ number_format($row['expected_loan'], 2) }}
                            </span>
                            @if(count($row['loan_details']) > 1)
                            <div class="loan-breakdown">
                                @foreach($row['loan_details'] as $ld)
                                <div>{{ $ld['loan_number'] }}: {{ number_format($ld['amount_per_installment'], 2) }}</div>
                                @endforeach
                            </div>
                            @endif
                        @else
                            <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">TOTALS &nbsp; ({{ count($rows) }} members)</td>
                    <td class="num">KES {{ number_format($totalSavings, 2) }}</td>
                    <td class="num">KES {{ number_format($totalLoanPayments, 2) }}</td>
                    <td class="num">KES {{ number_format($totalExpected, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- ── RATIO NOTE ──────────────────────────────────────── --}}
    @if($hasMultiLoanMembers)
    <div class="ratio-note">
        <strong>Loan Distribution Note:</strong>
        For members with multiple active loans, the received loan payment is distributed
        proportionally based on each loan's installment amount relative to the total expected
        installment. Any shortfall is added to the next period's installment for the respective loan.
    </div>
    @endif

    {{-- ── SIGNATURES ──────────────────────────────────────── --}}
    <div class="sig-line">
        <div class="sig-item">
            <div class="line"></div>
            <label>Loan Officer Signature</label>
        </div>
        <div class="sig-item">
            <div class="line"></div>
            <label>Group Chairperson Signature</label>
        </div>
        <div class="sig-item">
            <div class="line"></div>
            <label>Date</label>
        </div>
    </div>

    {{-- ── FOOTER ───────────────────────────────────────────── --}}
    <div class="footer">
        <span>{{ $orgName }} — Confidential</span>
        <span>Group Payment Report &bull; {{ $group->name }} &bull; {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span>
        <span>Printed: {{ now()->format('d M Y H:i') }}</span>
    </div>

</body>
</html>