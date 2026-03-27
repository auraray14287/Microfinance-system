<!DOCTYPE html>
<html>
<head>
    <title>Loan Application Form</title>
    <style>
        @page {
            margin: 15mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            font-weight: bold;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .logo-section {
            display: table-cell;
            width: 20%;
            vertical-align: top;
            padding-right: 15px;
        }

        .logo-section img {
            width: 170px;
            height: 90px;
            object-fit: contain;
        }

        .company-info {
            display: table-cell;
            width: 80%;
            vertical-align: top;
        }

        .company-name {
            font-size: 17px;
            font-weight: bold;
            color: #2d5016;
            margin-bottom: 4px;
        }

        .contact-info {
            font-size: 11px;
            font-weight: bold;
            line-height: 1.6;
            color: #666;
        }

        .slogan {
            font-style: italic;
            font-size: 12px;
            font-weight: bold;
            color: #8b6914;
            margin-top: 5px;
        }

        .decorative-line {
            height: 2px;
            margin: 4px 0;
        }

        .line-green { background-color: #2d5016; }
        .line-gold  { background-color: #8b6914; }

        .form-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 14px 0 4px 0;
        }

        .loan-meta {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 16px 0 8px 0;
            padding-bottom: 4px;
            border-bottom: 2px solid #000;
        }

        .subsection-title {
            font-size: 12px;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0 8px 0;
        }

        .form-field {
            margin-bottom: 10px;
        }

        .field-label {
            font-weight: bold;
        }

        .field-value {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 280px;
            padding-bottom: 1px;
            margin-left: 4px;
        }

        .field-value-short {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 45px;
            margin: 0 2px;
            padding-bottom: 1px;
        }

        .checkbox {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            margin-right: 4px;
            vertical-align: middle;
        }

        .checkbox-filled {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            background-color: #000;
            margin-right: 4px;
            vertical-align: middle;
        }

        .checkbox-item {
            display: inline-block;
            margin-right: 14px;
            margin-top: 4px;
        }

        .checkbox-label {
            vertical-align: middle;
        }

        .loan-details-box {
            border: 1px solid #ccc;
            padding: 10px 15px;
            margin: 8px 0;
        }

        .loan-details-row {
            margin-bottom: 6px;
        }

        .section-divider {
            border-top: 1px solid #000;
            margin: 16px 0;
        }

        .office-use-section {
            margin-top: 20px;
            padding: 12px;
            border: 2px solid #000;
            page-break-inside: avoid;
        }

        .office-use-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .declaration-text {
            font-size: 11px;
            font-weight: bold;
            line-height: 1.6;
            text-align: justify;
            margin: 12px 0;
        }

        .watermark {
            position: fixed;
            bottom: 50px;
            right: 50px;
            font-size: 120px;
            color: rgba(0,0,0,0.05);
            z-index: -1;
            font-weight: bold;
        }

        .guarantor-block {
            margin-bottom: 12px;
        }

        .signature-section {
            margin: 16px 0;
        }
    </style>
</head>
<body>

<div class="watermark">RBM</div>

{{-- ===== HEADER ===== --}}
<div class="header-container">
    <div class="logo-section">
        @php
            // Priority: 1) hardcoded RafikiBora logo, 2) Spatie media, 3) public fallbacks
            $logoPath = null;
            $hardcoded = public_path('images/rafikibora-logo.png');
            if (file_exists($hardcoded)) {
                $logoPath = $hardcoded;
            } elseif (file_exists(public_path('companyLogo.png'))) {
                $logoPath = public_path('companyLogo.png');
            } elseif (file_exists(public_path('logo.png'))) {
                $logoPath = public_path('logo.png');
            } elseif (file_exists(public_path('logo.jpg'))) {
                $logoPath = public_path('logo.jpg');
            } elseif ($user && $user->hasMedia('company_logo')) {
                $media = $user->getFirstMedia('company_logo');
                $logoPath = $media ? $media->getPath() : null;
            }
        @endphp
        @if($logoPath && file_exists($logoPath))
            <img src="{{ $logoPath }}" alt="Company Logo">
        @else
            <div style="width:90px;height:90px;border:2px solid #8b6914;border-radius:45px;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#2d5016;font-size:22px;">RBM</div>
        @endif
    </div>
    <div class="company-info">
        <div class="company-name">
            @php
                // $companyName from the controller equals the user's personal name ("Raymond").
                // Use organization name if available, otherwise hardcode.
                $displayCompany = $user->organization->name
                               ?? $user->company_name
                               ?? $user->organisation_name
                               ?? 'RAFIKI BORA MICROFINANCE';
            @endphp
            {{ strtoupper($displayCompany) }}
        </div>
        <div class="contact-info">
            <div>Phone: {{ $user->company_phone ?? '+254 700 000 000' }}</div>
            @php $emailDisplay = $user->company_representative_email ?? ('info' . '@' . 'rafikibora.co.ke'); @endphp
            <div>Email: {{ $emailDisplay }}</div>
            <div>Location: {{ $user->company_address ?? 'Kenya' }}</div>
        </div>
        <div class="slogan">Empowering Communities Through Financial Inclusion</div>
        <div class="decorative-line line-green"></div>
        <div class="decorative-line line-gold"></div>
    </div>
</div>

{{-- ===== TITLE ===== --}}
<div class="form-title">LOAN APPLICATION FORM</div>

@php
    // Resolve borrower — prefer Borrower model, fall back to Member
    $borrower = $loan->borrower ?? $loan->member ?? null;

    // Resolve display name fields
    $firstName  = $borrower->first_name  ?? '';
    $middleName = $borrower->middle_name ?? '';
    $lastName   = $borrower->last_name   ?? '';
    $fullName   = trim("$firstName $middleName $lastName");

    // ── Loan Type ──────────────────────────────────────────────────────────
    // loan_type can be a JSON string, an object/array, or a plain string
    $rawLoanType = $loan->loan_type ?? null;
    if (is_string($rawLoanType)) {
        $decoded = json_decode($rawLoanType, true);
        $loanType = (is_array($decoded) && isset($decoded['loan_name']))
            ? $decoded['loan_name']
            : $rawLoanType;
    } elseif (is_array($rawLoanType)) {
        $loanType = $rawLoanType['loan_name'] ?? 'Normal loan';
    } elseif (is_object($rawLoanType)) {
        $loanType = $rawLoanType->loan_name ?? $rawLoanType->name ?? 'Normal loan';
    } else {
        // Try relationship
        $loanType = $loan->loanType->loan_name ?? $loan->loanType->name ?? 'Normal loan';
    }

    // ── Product / Asset ────────────────────────────────────────────────────
    $productName        = $loan->product->name        ?? null;
    $productDescription = $loan->product_description  ?? null;
    // Normalize "week(s)" → "Week", "month(s)" → "Month", etc.
    $rawPeriod    = $loan->duration_period ?? 'Week';
    $cleanPeriod  = ucfirst(strtolower(preg_replace('/\(s\)/i', '', $rawPeriod)));
    $cleanPeriod  = rtrim($cleanPeriod); // e.g. "Week", "Month"

    // ── Financials ─────────────────────────────────────────────────────────
    $principalAmount = $loan->principal_amount ?? 0;
    $processingFee   = $loan->processing_fee   ?? $loan->service_fee ?? 0;
    $interestRate    = $loan->interest_rate     ?? 0;
    $interestAmount  = $loan->interest_amount   ?? 0;
    $totalRepayment  = $loan->repayment_amount  ?? 0;
    $loanDuration    = $loan->loan_duration     ?? '';
    $installmentAmt  = ($loanDuration > 0) ? ($totalRepayment / $loanDuration) : 0;

    // ── Dates ──────────────────────────────────────────────────────────────
    $releaseDate     = $loan->loan_release_date ?? $loan->disbursement_date ?? $loan->release_date ?? $loan->created_at ?? null;
    $nextPaymentDate = $loan->next_payment_date  ?? null;
    $clearanceDate   = $loan->clearance_date     ?? $loan->maturity_date ?? $loan->expected_maturity_date ?? null;

    $formatDate = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d/m/Y') : '';

    // ── Member field aliases (confirmed column names from members table) ──
    $phone = $borrower->mobile_no ?? $borrower->mobile ?? $borrower->phone_number ?? $borrower->phone ?? '';

    // Physical address: physical_address + village = "SEME KADERO MARKET, KADERO"
    $addrPart1 = $borrower->physical_address ?? $borrower->address ?? '';
    $addrPart2 = $borrower->village ?? $borrower->location ?? $borrower->estate ?? '';
    $address   = $addrPart2
               ? strtoupper(trim($addrPart1)) . ', ' . strtoupper(trim($addrPart2))
               : strtoupper(trim($addrPart1));

    // Town / County: town + county
    $townPart   = $borrower->town    ?? $borrower->city    ?? '';
    $countyPart = $borrower->county  ?? $borrower->province ?? '';
    $townCounty = $countyPart
                ? strtoupper(trim($townPart)) . ', ' . strtoupper(trim($countyPart))
                : strtoupper(trim($townPart));

    $subCounty  = $borrower->sub_county    ?? '';
    $nearestMkt = $borrower->nearest_market ?? '';

    // Group name — group_id is an integer on the member; load via loan->group relationship
    $groupName = '';
    try { $groupName = $loan->group->name ?? ''; } catch (\Exception $e) {}
    if (!$groupName) {
        try { $groupName = $borrower->group->name ?? ''; } catch (\Exception $e) {}
    }
    if (!$groupName && ($borrower->group_id ?? null)) {
        try {
            $grp = \App\Models\Group::find($borrower->group_id);
            $groupName = $grp->name ?? '';
        } catch (\Exception $e) {}
    }

    // Business fields
    $bizName      = $borrower->business_name    ?? $borrower->occupation ?? '';
    $bizAddress   = $borrower->business_address ?? '';
    $bizTownPart  = $borrower->business_town    ?? '';
    $bizCntyPart  = $borrower->business_county  ?? '';
    $bizTown      = $bizCntyPart
                  ? strtoupper(trim($bizTownPart)) . ', ' . strtoupper(trim($bizCntyPart))
                  : strtoupper(trim($bizTownPart));
    $bizSubCounty = $borrower->business_sub_county ?? '';

    // Next of kin — confirmed column names: kin_name, kin_mobile, kin_village, kin_town, kin_county, kin_sub_county
    $kinName      = $borrower->kin_name          ?? '';
    $kinPhone     = $borrower->kin_mobile        ?? $borrower->kin_phone ?? '';
    $kinVillage   = $borrower->kin_village       ?? '';
    $kinTownPart  = $borrower->kin_town          ?? '';
    $kinCntyPart  = $borrower->kin_county        ?? '';
    $kinTown      = $kinCntyPart
                  ? strtoupper(trim($kinTownPart)) . ', ' . strtoupper(trim($kinCntyPart))
                  : strtoupper(trim($kinTownPart));
    $kinSubCounty = $borrower->kin_sub_county    ?? $borrower->kin_sub_location ?? '';

    // Guarantors — stored directly on member: guarantor1_name, guarantor1_mobile, guarantor1_relationship
    $guarantors = collect([
        (object)[
            'first_name'   => $borrower->guarantor1_name         ?? '',
            'last_name'    => '',
            'mobile'       => $borrower->guarantor1_mobile       ?? '',
            'relationship' => $borrower->guarantor1_relationship ?? '',
        ],
        (object)[
            'first_name'   => $borrower->guarantor2_name         ?? '',
            'last_name'    => '',
            'mobile'       => $borrower->guarantor2_mobile       ?? '',
            'relationship' => $borrower->guarantor2_relationship ?? '',
        ],
    ]);
@endphp



<div class="loan-meta">
    Loan ID: <strong>{{ $loan->loan_number ?? '' }}</strong>
    &nbsp;&nbsp;&nbsp;
    Date: <strong>{{ $formatDate($releaseDate) }}</strong>
    &nbsp;&nbsp;&nbsp;
    Loan Type: <strong>{{ $loanType }}</strong>
</div>

{{-- ===== SECTION A: APPLICANT INFORMATION ===== --}}
<div class="section-title">SECTION A: APPLICANT INFORMATION</div>
<div class="subsection-title">Personal Details</div>

<div class="form-field">
    <span class="field-label">1. Full Name:</span>
    <span class="field-value">{{ strtoupper($fullName) }}</span>
</div>

<div class="form-field">
    <span class="field-label">2. ID Number:</span>
    <span class="field-value">{{ $borrower->id_number ?? $borrower->identification ?? '' }}</span>
</div>

<div class="form-field">
    <span class="field-label">3. Date of Birth:</span>
    @if($borrower && ($borrower->dob ?? null))
        @php $dob = \Carbon\Carbon::parse($borrower->dob); @endphp
        <span class="field-value-short">{{ $dob->format('d') }}</span> /
        <span class="field-value-short">{{ $dob->format('m') }}</span> /
        <span class="field-value-short">{{ $dob->format('Y') }}</span>
    @else
        <span class="field-value-short"></span> /
        <span class="field-value-short"></span> /
        <span class="field-value-short"></span>
    @endif
</div>

<div class="form-field">
    <span class="field-label">4. Gender:</span>
    <span class="checkbox-item">
        @if($borrower && strtolower($borrower->gender ?? '') === 'male')
            <span class="checkbox-filled"></span>
        @else
            <span class="checkbox"></span>
        @endif
        <span class="checkbox-label">Male</span>
    </span>
    <span class="checkbox-item">
        @if($borrower && strtolower($borrower->gender ?? '') === 'female')
            <span class="checkbox-filled"></span>
        @else
            <span class="checkbox"></span>
        @endif
        <span class="checkbox-label">Female</span>
    </span>
</div>

<div class="form-field">
    <span class="field-label">5. Marital Status:</span>
    @foreach(['Single','Married','Divorced','Widowed'] as $status)
        <span class="checkbox-item">
            @if($borrower && strtolower($borrower->marital_status ?? '') === strtolower($status))
                <span class="checkbox-filled"></span>
            @else
                <span class="checkbox"></span>
            @endif
            <span class="checkbox-label">{{ $status }}</span>
        </span>
    @endforeach
</div>

<div class="form-field">
    <span class="field-label">6. Mobile Number:</span>
    <span class="field-value">{{ $phone }}</span>
</div>

<div class="form-field">
    <span class="field-label">7. Physical Address:</span>
    <span class="field-value">{{ $address }}</span>
</div>

<div class="form-field">
    <span class="field-label">8. Town / County:</span>
    <span class="field-value">{{ strtoupper($townCounty) }}</span>
</div>

<div class="form-field">
    <span class="field-label">9. Sub County:</span>
    <span class="field-value">{{ strtoupper($subCounty) }}</span>
</div>

<div class="form-field">
    <span class="field-label">10. Nearest Market:</span>
    <span class="field-value">{{ strtoupper($nearestMkt) }}</span>
</div>

<div class="form-field">
    <span class="field-label">11. Group Name:</span>
    <span class="field-value">{{ strtoupper($groupName) }}</span>
</div>

{{-- ===== SECTION B: BUSINESS INFORMATION ===== --}}
<div class="section-title">SECTION B: BUSINESS INFORMATION</div>

<div class="form-field">
    <span class="field-label">1. Business Name:</span>
    <span class="field-value">{{ $bizName }}</span>
</div>

<div class="form-field">
    <span class="field-label">2. Business Address:</span>
    <span class="field-value">{{ $bizAddress }}</span>
</div>

<div class="form-field">
    <span class="field-label">3. Business Town / County:</span>
    <span class="field-value">{{ strtoupper($bizTown) }}</span>
</div>

<div class="form-field">
    <span class="field-label">4. Business Sub County:</span>
    <span class="field-value">{{ strtoupper($bizSubCounty) }}</span>
</div>

{{-- ===== SECTION C: LOAN DETAILS ===== --}}
<div class="section-title">SECTION C: LOAN DETAILS</div>

<div class="loan-details-box">
    <div class="loan-details-row"><span class="field-label">Loan Type:</span> {{ $loanType }}</div>

    {{-- ── Asset Product ── --}}
    @if($productName)
    <div class="loan-details-row" style="margin-top:6px;padding-top:6px;border-top:1px dashed #ccc;">
        <span class="field-label">Asset Product:</span> {{ $productName }}
    </div>
    @endif
    @if($productDescription)
    <div class="loan-details-row">
        <span class="field-label">Asset Description:</span> {{ $productDescription }}
    </div>
    @endif
    @if($productName || $productDescription)
    <div style="border-top:1px dashed #ccc;margin:6px 0;"></div>
    @endif

    <div class="loan-details-row"><span class="field-label">Principal Amount (KES):</span> {{ number_format($principalAmount, 2) }}</div>
    <div class="loan-details-row"><span class="field-label">Processing Fee (KES):</span> {{ number_format($processingFee, 2) }}</div>
    <div class="loan-details-row"><span class="field-label">Interest Rate (% per month):</span> {{ $interestRate }}%</div>
    <div class="loan-details-row"><span class="field-label">Interest Amount (KES):</span> {{ number_format($interestAmount, 2) }}</div>
    <div class="loan-details-row"><span class="field-label">Total Repayment (KES):</span> {{ number_format($totalRepayment, 2) }}</div>
    <div class="loan-details-row"><span class="field-label">Installment Type:</span> {{ $cleanPeriod }}ly</div>
    <div class="loan-details-row"><span class="field-label">Loan Duration:</span> {{ $loanDuration }} {{ $cleanPeriod }}s</div>
    <div class="loan-details-row"><span class="field-label">{{ $cleanPeriod }}ly Installment (KES):</span> {{ number_format($installmentAmt, 2) }}</div>
    <div class="loan-details-row"><span class="field-label">Loan Release Date:</span> {{ $formatDate($releaseDate) }}</div>
    <div class="loan-details-row"><span class="field-label">Next Payment Date:</span> {{ $formatDate($nextPaymentDate) }}</div>
    <div class="loan-details-row"><span class="field-label">Clearance Date:</span> {{ $formatDate($clearanceDate) }}</div>
</div>

{{-- ===== SECTION D: NEXT OF KIN ===== --}}
<div class="section-title">SECTION D: NEXT OF KIN</div>

<div class="form-field">
    <span class="field-label">1. Full Name:</span>
    <span class="field-value">{{ strtoupper($kinName) }}</span>
</div>

<div class="form-field">
    <span class="field-label">2. Mobile Number:</span>
    <span class="field-value">{{ $kinPhone }}</span>
</div>

<div class="form-field">
    <span class="field-label">3. Village:</span>
    <span class="field-value">{{ strtoupper($kinVillage) }}</span>
</div>

<div class="form-field">
    <span class="field-label">4. Town / County:</span>
    <span class="field-value">{{ strtoupper($kinTown) }}</span>
</div>

<div class="form-field">
    <span class="field-label">5. Sub County:</span>
    <span class="field-value">{{ strtoupper($kinSubCounty) }}</span>
</div>

{{-- ===== SECTION E: GUARANTORS ===== --}}
<div class="section-title">SECTION E: GUARANTORS</div>

@foreach([1,2] as $i)
    @php $g = $guarantors->get($i - 1); @endphp
    <div class="guarantor-block">
        <div class="subsection-title" style="text-decoration:none;">Guarantor {{ $i }}</div>
        <div class="form-field">
            <span class="field-label">Full Name:</span>
            <span class="field-value">{{ $g ? strtoupper(trim($g->first_name ?? '')) : '' }}</span>
        </div>
        <div class="form-field">
            <span class="field-label">Mobile Number:</span>
            <span class="field-value">{{ $g->mobile ?? '' }}</span>
        </div>
        <div class="form-field">
            <span class="field-label">Relationship:</span>
            <span class="field-value">{{ ucfirst($g->relationship ?? '') }}</span>
        </div>
    </div>
@endforeach

{{-- ===== SECTION F: DECLARATION & CONSENT ===== --}}
<div class="section-divider"></div>
<div class="section-title">SECTION F: DECLARATION & CONSENT</div>

<div class="declaration-text">
    I, the undersigned, hereby declare that the information provided above is true and correct to the best of my knowledge.
    I authorize {{ strtoupper($displayCompany ?? 'RAFIKI BORA MICROFINANCE') }} to verify the information provided and use it for loan assessment purposes.
    I acknowledge and agree to the terms and conditions of the loan agreement.
</div>

<div class="signature-section">
    <div class="form-field">
        <span class="field-label">Applicant's Full Name:</span>
        <span class="field-value" style="min-width:360px;">{{ strtoupper($fullName) }}</span>
    </div>
    <div class="form-field">
        <span class="field-label">Signature:</span>
        <span class="field-value" style="min-width:300px;"></span>
    </div>
    <div class="form-field">
        <span class="field-label">Date:</span>
        <span class="field-value-short"></span> /
        <span class="field-value-short"></span> /
        <span class="field-value-short"></span>
    </div>
</div>

{{-- ===== FOR OFFICE USE ONLY ===== --}}
<div class="office-use-section">
    <div class="office-use-title">For Office Use Only</div>

    <div class="form-field">
        <span class="field-label">Loan ID:</span>
        <span class="field-value">{{ $loan->loan_number ?? '' }}</span>
    </div>

    <div class="form-field">
        <span class="field-label">Loan Status:</span>
        <span class="field-value">{{ ucfirst(str_replace('_', ' ', $loan->loan_status ?? '')) }}</span>
    </div>

    <div class="form-field" style="margin-top:10px;">
        <span class="field-label">Principal Amount (KES):</span>
        <span class="field-value">{{ number_format($principalAmount, 2) }}</span>
    </div>

    <div class="form-field">
        <span class="field-label">Total Repayment (KES):</span>
        <span class="field-value">{{ number_format($totalRepayment, 2) }}</span>
    </div>

    <div class="form-field">
        <span class="field-label">Repayment Term:</span>
        <span class="field-value">{{ $loanDuration }} {{ $cleanPeriod }}s</span>
    </div>

    <div class="form-field">
        <span class="field-label">Interest Rate:</span>
        <span class="field-value">{{ $interestRate }}% per month</span>
    </div>

    <div class="form-field">
        <span class="field-label">Loan Processing Officer:</span>
        <span class="field-value">{{ $user->name ?? auth()->user()->name ?? '' }}</span>
    </div>

    <div class="form-field">
        <span class="field-label">Date Processed:</span>
        <span class="field-value">{{ $formatDate($releaseDate) }}</span>
    </div>

    <div style="margin-top:12px;">
        <span class="checkbox-item"><span class="checkbox"></span><span class="checkbox-label">Application Received</span></span>
        <span class="checkbox-item"><span class="checkbox"></span><span class="checkbox-label">Documents Complete</span></span>
        <span class="checkbox-item"><span class="checkbox"></span><span class="checkbox-label">Loan Approved</span></span>
        <span class="checkbox-item"><span class="checkbox"></span><span class="checkbox-label">Not Approved</span></span>
    </div>

    <div class="form-field" style="margin-top:14px;">
        <span class="field-label">Authorizing Officer Signature:</span>
        <span class="field-value" style="min-width:280px;"></span>
    </div>
</div>

</body>
</html>