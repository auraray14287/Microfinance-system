<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { color: #1A5276; font-size: 18px; text-align: center; }
        h2 { color: #1F618D; font-size: 14px; border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-top: 20px; }
        .subtitle { text-align: center; color: #888; font-size: 11px; margin-bottom: 20px; }
        .label { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1A5276; color: white; padding: 6px; text-align: left; }
        td { padding: 6px; border-bottom: 1px solid #eee; }
        tfoot td { font-weight: bold; background: #f5f5f5; }
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; color: #888; font-size: 10px; }
    </style>
</head>
<body>

    <h1>Rafiki Bora Microfinance</h1>
    <p class="subtitle">Member Profile Report — Generated on {{ now()->format('d M Y H:i') }}</p>

    <h2>Personal Information</h2>
    <table>
        <tr>
            <td><span class="label">Full Name:</span> {{ $member->first_name }} {{ $member->middle_name }} {{ $member->last_name }}</td>
            <td><span class="label">ID Number:</span> {{ $member->id_number }}</td>
        </tr>
        <tr>
            <td><span class="label">Gender:</span> {{ ucfirst($member->gender) }}</td>
            <td><span class="label">Date of Birth:</span> {{ $member->dob }}</td>
        </tr>
        <tr>
            <td><span class="label">Marital Status:</span> {{ ucfirst($member->marital_status) }}</td>
            <td><span class="label">Mobile:</span> {{ $member->mobile_no }}</td>
        </tr>
        <tr>
            <td><span class="label">Status:</span>
                <span class="{{ $member->status === 'active' ? 'status-active' : 'status-inactive' }}">
                    {{ ucfirst($member->status) }}
                </span>
            </td>
            <td><span class="label">Group:</span> {{ $member->groups->pluck('name')->join(', ') }}</td>
        </tr>
        <tr>
            <td><span class="label">Physical Address:</span> {{ $member->physical_address }}</td>
            <td><span class="label">Village:</span> {{ $member->village }}</td>
        </tr>
        <tr>
            <td><span class="label">Town:</span> {{ $member->town }}</td>
            <td><span class="label">County:</span> {{ $member->county }}</td>
        </tr>
    </table>

    <h2>Savings Statement</h2>
    @if ($member->savings->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount (KES)</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($member->savings as $saving)
                    <tr>
                        <td>{{ $saving->contribution_date }}</td>
                        <td>{{ number_format($saving->amount, 2) }}</td>
                        <td>{{ $saving->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td>KES {{ number_format($member->savings->sum('amount'), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @else
        <p>No savings records found.</p>
    @endif

    <h2>Loan Statement</h2>
    @if ($member->loans->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Amount (KES)</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($member->loans as $loan)
                    <tr>
                        <td>{{ $loan->id }}</td>
                        <td>{{ number_format($loan->amount, 2) }}</td>
                        <td>{{ ucfirst($loan->status) }}</td>
                        <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No loan records found.</p>
    @endif

    <div class="footer">Rafiki Bora Microfinance — Confidential</div>

</body>
</html>