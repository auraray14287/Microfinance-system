<?php

namespace App\Filament\Resources;

use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Forms\Components\Toggle;
use App\helpers\CreateLinks;
use Carbon\Carbon;
use Filament\Forms\Set;
use Filament\Forms\Get;
use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Support\Facades\Storage;
use App\Models\Loan;
use App\Models\Group;
use App\Models\Member;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\LoanExporter;
use Filament\Tables\Actions\ExportAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    private const WEEKS_PER_MONTH_INTEREST = 4;  // 1 month = 4 weeks for interest
    private const WEEKS_PER_MONTH_SCHEDULE = 3; // 1 month = 3 weeks for repayment schedule

    protected static ?string $navigationGroup = 'Loans';
    protected static ?string $navigationIcon = 'fas-dollar-sign';
    protected static ?string $navigationLabel = 'Loans';
    protected static ?int $navigationSort = 2;
    protected static bool $shouldRegisterNavigation = true;
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationItems(): array
    {
        $items = parent::getNavigationItems();
        
        // Exclude cash flow statement from making Loans navigation active
        $excludedPaths = [
            'admin/loans/cash-flow-statement',
        ];
        
        $currentPath = request()->path();
        
        if (in_array($currentPath, $excludedPaths)) {
            // Create new navigation items with custom active check that always returns false
            return array_map(function ($item) {
                // Clone the item and override isActiveWhen to return false
                $newItem = \Filament\Navigation\NavigationItem::make($item->getLabel())
                    ->url($item->getUrl())
                    ->icon($item->getIcon())
                    ->group($item->getGroup())
                    ->sort($item->getSort())
                    ->isActiveWhen(fn (): bool => false);
                
                // Add badge if it exists
                if ($badge = $item->getBadge()) {
                    $newItem->badge($badge);
                }
                
                return $newItem;
            }, $items);
        }
        
        return $items;
    }


public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\Select::make('loan_type_id')
            ->label('Loan Type')
            ->options(\App\Models\LoanType::pluck('loan_name', 'id'))
            ->searchable()
            ->required()
            ->live()
            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                if ($state) {
                    $loanType = \App\Models\LoanType::find($state);
                    if ($loanType) {
                        $set('interest_rate', $loanType->interest_rate);
                        $set('duration_period', $loanType->interest_cycle);
                    }
                    $principal = (float)($get('principal_calc') ?? 0);
                    if ($principal <= 0) {
                        $rows = $get('loanProducts') ?? [];
                        $principal = collect($rows)->sum(fn($row) => (float)($row['subtotal'] ?? 0));
                    }
                    if ($principal > 0) {
                        self::recalculateLoan($set, $get, $principal, (int) $state);
                    }
                }
            }),
        
        \Filament\Forms\Components\Section::make('Asset Products')
            ->schema([
                \Filament\Forms\Components\ViewField::make('products_livewire')
                    ->view('filament.forms.components.loan-product-table')
                    ->columnSpanFull()
                    ->dehydrated(false),
            ])
            ->columnSpanFull(),

        Forms\Components\Textarea::make('product_description')
            ->label('Product / Asset Description')
            ->disabled()
            ->dehydrated(true)
            ->helperText('Auto-filled from selected products above.')
            ->rows(2)
            ->columnSpanFull()
            ->nullable(),

                Forms\Components\TextInput::make('member_id_number')
            ->label('Borrower ID (Member ID Number)')
            ->required()
            ->dehydrated(false)
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, Set $set) {
                if ($state) {
                    $user = auth()->user();
                    $query = Member::where('id_number', $state);
                    if (!$user->hasRole('super_admin')) {
                        $groupIds = Group::where('assigned_officer', $user->id)->pluck('id');
                        $query->whereIn('group_id', $groupIds);
                    }
                    $member = $query->first();
                    if ($member) {
                        $set('member_id', $member->id);
                        $set('borrower_name', trim("{$member->first_name} {$member->middle_name} {$member->last_name}"));
                    } else {
                        $set('member_id', null);
                        $set('borrower_name', '');
                    }
                } else {
                    $set('member_id', null);
                    $set('borrower_name', '');
                }
            }),

        Forms\Components\TextInput::make('borrower_name')
            ->disabled()
            ->dehydrated(false),

        Hidden::make('member_id'),

        Forms\Components\Hidden::make('loan_status')->default('requested'),

        Forms\Components\TextInput::make('principal_amount')
            ->numeric()
            ->disabled()
            ->dehydrated(true)
            ->helperText('Auto-calculated from Quantity x Reference Price.'),
        Forms\Components\TextInput::make('principal_calc')
            ->hidden()
            ->live()
            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                $principal = (float)($state ?? 0);
                $set('principal_amount', $principal);
                $loanTypeId = (int)($get('loan_type_id') ?? 0);
                if ($principal > 0 && $loanTypeId > 0) {
                    self::recalculateLoan($set, $get, $principal, $loanTypeId);
                }
            }),

        Forms\Components\TextInput::make('loan_amount')
            ->label('Loan Amount (Principal + Processing Fee) (KES)')
            ->disabled()->dehydrated(true)
            ->numeric(),

        Forms\Components\TextInput::make('service_fee')
            ->label('Processing Fee (KES)')
            ->disabled()->dehydrated(true)
            ->numeric(),

        Forms\Components\TextInput::make('interest_rate')
            ->label('Interest Rate (% per month)')
            ->disabled()->dehydrated(true)
            ->numeric(),

        Forms\Components\Select::make('duration_period')
            ->label('Loan Duration')
            ->options([
                'week(s)' => 'Weekly',
                'month(s)' => 'Monthly',
            ])
            ->required()
            ->live()
            ->afterStateUpdated(function (Set $set) {
                $set('loan_duration', null);
                $set('next_payment_date', null);
                $set('clearance_date', null);
                $set('amount_per_installment', null);
                $set('repayment_amount', null);
                $set('interest_amount', null);
                $set('loan_amount', null);
            }),

        Forms\Components\TextInput::make('loan_duration')
            ->label('Loan Period (number of weeks or months)')
            ->helperText('e.g. enter 6 for 6 months. SHORT TERM: max 2 months (6 weeks). MEDIUM SIZE: max 10 months (30 weeks). LONG TERM: max 24 months (72 weeks). SPECIAL CATEGORY: max 5 months (15 weeks).')
            ->numeric()
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                $duration = (int)($state ?? 0);
                $loanTypeId = (int)($get('loan_type_id') ?? 0);
                $durationPeriod = $get('duration_period') ?? 'month(s)';

                // Duration limits in months (3 weeks = 1 month)
                $limits = [3 => 2, 4 => 10, 5 => 24, 6 => 5];
                $names  = [3 => 'SHORT TERM', 4 => 'MEDIUM SIZE', 5 => 'LONG TERM', 6 => 'SPECIAL CATEGORY'];

                if ($loanTypeId && isset($limits[$loanTypeId]) && $duration > 0) {
                    $maxMonths = $limits[$loanTypeId];
                    $maxWeeks  = $maxMonths * 3;
                    $durationInMonths = ($durationPeriod === 'week(s)')
                        ? $duration / 3
                        : $duration;

                    if ($durationInMonths > $maxMonths) {
                        $label = $names[$loanTypeId] ?? 'this loan type';
                        $msg = $durationPeriod === 'week(s)'
                            ? "Duration exceeds limit for {$label}. Maximum is {$maxWeeks} weeks ({$maxMonths} months)."
                            : "Duration exceeds limit for {$label}. Maximum is {$maxMonths} months ({$maxWeeks} weeks).";
                        $set('duration_error', $msg);
                        return;
                    }
                }

                $set('duration_error', null);
                $principal = (float)($get('principal_calc') ?? $get('principal_amount') ?? 0);
                if ($principal > 0 && $loanTypeId > 0) {
                    self::recalculateLoan($set, $get, $principal, $loanTypeId);
                }
            }),

        Forms\Components\Placeholder::make('duration_error')
            ->label('')
            ->content(fn (Get $get) => $get('duration_error') ?? '')
            ->visible(fn (Get $get) => !empty($get('duration_error')))
            ->extraAttributes(['class' => 'text-danger-600 text-sm font-medium']),

        Forms\Components\DatePicker::make('loan_release_date')
            ->required()
            ->native(false)
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                if ($state) {
                    self::recalculateDates($set, $get, $state);
                }
            }),

        Forms\Components\DatePicker::make('next_payment_date')
            ->disabled()->dehydrated(true)
            ->native(false),

        Forms\Components\TextInput::make('repayment_amount')
            ->label('Total Repayment Amount (Loan Amount + Interest) (KES)')
            ->disabled()->dehydrated(true)
            ->numeric(),

        Forms\Components\TextInput::make('deposit')
            ->label('Required Deposit (KES)')
            ->disabled()->dehydrated(true)
            ->numeric(),

        Forms\Components\Placeholder::make('member_deposit_balance')
            ->label('Member Deposit Balance (KES)')
            ->content(function (Get $get) {
                $memberId = $get('member_id');
                if (!$memberId) return 'Select a member first';
                $balance = \App\Models\MemberDeposit::getBalance((int)$memberId);
                return 'KES ' . number_format($balance, 2);
            })
            ->visible(fn (Get $get) => in_array((int)$get('loan_type_id'), [5, 6])),



        Forms\Components\TextInput::make('interest_amount')
            ->disabled()->dehydrated(true)
            ->numeric(),

        Forms\Components\TextInput::make('amount_per_installment')
            ->label('Amount Per Installment (KES)')
            ->helperText('Loan Amount / Loan Period')
            ->disabled()->dehydrated(true)
            ->numeric(),

        Forms\Components\DatePicker::make('clearance_date')
            ->label('Clearance Date (Final Installment Date)')
            ->disabled()->dehydrated(true)
            ->native(false),

        Hidden::make('loan_number'),
        Hidden::make('balance'),
        Hidden::make('disbursed_amount'),
    ]);
}

private static function recalculateLoan(Set $set, Get $get, float $principal, int $loanTypeId): void
{
    $loanType = \App\Models\LoanType::find($loanTypeId);
    if (!$loanType) return;

    $fee = 0;
    if ($loanType->service_fee_type === 'service_fee_percentage') {
        $fee = $principal * ($loanType->service_fee_percentage / 100);
    } elseif ($loanType->service_fee_type === 'service_fee_custom_amount') {
        $fee = (float) $loanType->service_fee_custom_amount;
    }

    $loanAmount = $principal + $fee;
    $interestRate = (float) ($loanType->interest_rate ?? 0);
    $loanDuration = (int) ($get('loan_duration') ?? 0);
    $durationPeriod = $get('duration_period') ?? 'month(s)';

    $months = ($durationPeriod === 'week(s)') ? $loanDuration / self::WEEKS_PER_MONTH_INTEREST : $loanDuration;
    $interest = $principal * ($interestRate / 100) * $months;
    $totalRepayment = $loanAmount + $interest;
    $depositPercents = [5 => 30, 6 => 30];
    $depositPercent = $depositPercents[$loanTypeId] ?? 0;
    $deposit = $depositPercent > 0 ? round($principal * ($depositPercent / 100), 2) : 0;
    $remaining = $totalRepayment - $deposit;
    // Monthly installment = remaining / duration
    // Weekly installment = monthly installment / 3 (so 3 weekly = 1 monthly)
    if ($durationPeriod === 'week(s)') {
        $monthlyInstallment = $loanDuration > 0 ? $remaining / ($loanDuration / self::WEEKS_PER_MONTH_SCHEDULE) : 0;
        $installment = $monthlyInstallment / self::WEEKS_PER_MONTH_SCHEDULE;
    } else {
        $installment = $loanDuration > 0 ? $remaining / $loanDuration : 0;
    }

    $set('service_fee', round($fee, 2));
    $set('loan_amount', round($loanAmount, 2));
    $set('interest_rate', $interestRate);
    $set('interest_amount', round($interest, 2));
    $set('repayment_amount', round($totalRepayment, 2));
    $set('deposit', $deposit);
    $set('amount_per_installment', round($installment, 2));
    $set('balance', round($totalRepayment, 2));
    $set('disbursed_amount', round($principal - $fee, 2));

    $releaseDate = $get('loan_release_date');
    if ($releaseDate && $loanDuration > 0) {
        self::recalculateDates($set, $get, $releaseDate);
    }
}

private static function recalculateDates(Set $set, Get $get, string $releaseDate): void
{
    $duration = (int) ($get('loan_duration') ?? 0);
    $durationPeriod = $get('duration_period') ?? 'month(s)';

    if (!$releaseDate || $duration <= 0) return;

    $release = Carbon::parse($releaseDate);

    if ($durationPeriod === 'week(s)') {
        // Weekly loans: next payment = 1 week after release, clearance = release + duration weeks
        $nextPayment = $release->copy()->addWeek();
        $clearance = $release->copy()->addWeeks($duration);
    } else {
        // Monthly loans: next payment = 3 weeks after release, clearance = release + duration * 3 weeks
        $nextPayment = $release->copy()->addWeeks(3);
        $clearance = $release->copy()->addWeeks($duration * 3);
    }

    $set('next_payment_date', $nextPayment->format('Y-m-d'));
    $set('clearance_date', $clearance->format('Y-m-d'));
}

    public static function table(Table $table): Table
    {
        $create_link = new CreateLinks();
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(LoanExporter::class)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('loan_number')
                    ->label('Loan ID')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('member.first_name')
                    ->label('Borrower')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->member
                            ? "{$record->member->first_name} {$record->member->last_name}"
                            : '—'
                    )
                    ->searchable(),

                Tables\Columns\TextColumn::make('loan_type.loan_name')
                    ->label('Loan Type')
                    ->searchable(),

                Tables\Columns\TextColumn::make('loan_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'requested' => 'gray',
                        'approved' => 'success',
                        'denied' => 'danger',
                        'defaulted' => 'danger',
                        'fully_paid' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('principal_amount')
                    ->label('Principal (KES)')
                    ->money('KES')
                    ->sortable(),

                Tables\Columns\TextColumn::make('repayment_amount')
                    ->label('Total Repayment (KES)')
                    ->money('KES')
                    ->sortable(),

                Tables\Columns\TextColumn::make('loan_release_date')
                    ->label('Release Date')
                    ->date(),

                Tables\Columns\TextColumn::make('clearance_date')
                    ->label('Clearance Date')
                    ->date(),
                Tables\Columns\TextColumn::make('overdue_days')
                    ->label('Overdue Days')
                    ->getStateUsing(function ($record) {
                        if (!in_array($record->loan_status, ['approved', 'partially_paid', 'defaulted'])) return null;
                        if (!$record->next_payment_date) return null;
                        $days = (int) \Carbon\Carbon::parse($record->next_payment_date)->diffInDays(now(), false);
                        return $days > 0 ? $days : null;
                    })
                    ->formatStateUsing(fn ($state) => $state ? $state . ' days' : '—')
                    ->color(fn ($state) => $state > 30 ? 'danger' : ($state > 0 ? 'warning' : null))
                    ->sortable(false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('loan_status')
                    ->options([
                        'pending'   => 'Pending',
                        'requested' => 'Requested',
                        'processing' => 'Processing',
                        'approved' => 'Approved',
                        'denied' => 'Denied',
                        'defaulted' => 'Defaulted',
                        'partially_paid' => 'Partially Paid',
                        'fully_paid' => 'Fully Paid',

                    ]),

            ])
            ->actions([
                Tables\Actions\Action::make('previewApplication')
                    ->label('Preview Application')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->url(fn ($record) => route('loan.application.preview', ['id' => $record->id]))
                    ->openUrlInNewTab(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        if ($user->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }
        $groupIds = Group::where('assigned_officer', $user->id)->pluck('id');
        $memberIds = Member::whereIn('group_id', $groupIds)->pluck('id');
        return parent::getEloquentQuery()->whereIn('member_id', $memberIds);
    }

    public static function getPages(): array
    {
        return [
            'ai-assessment' => Pages\AIAssessment::route('/{record}/ai-assessment'),
            'cash-flow-statement' => Pages\CashFlowStatement::route('/cash-flow-statement'),
            'emi-schedule' => Pages\EMISchedule::route('/{record}/emi-schedule'),
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'view' => Pages\ViewLoan::route('/{record}'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),


        ];
    }

    /**
     * Calculate net pay based on the formula:
     * Net Pay = [(0.60 × Basic Pay) + Total Recurring Allowances] - [PAYE + Pension + Health Insurance + Other Statutory Deductions + Other Recurring Deductions]
     */
    protected static function calculateNetPay(Get $get, ?float $overrideAllowances = null): float
    {
        $basicPay = (float) ($get('basic_pay') ?? 0);
        $allowances = $overrideAllowances ?? (float) ($get('total_recurring_allowances') ?? 0);

        // Add other allowances to the total
        $otherAllowances = self::calculateTotalFromRepeater($get('other_allowances') ?? []);
        $totalAllowances = $allowances + $otherAllowances;

        $paye = (float) ($get('paye') ?? 0);
        $pension = (float) ($get('pension_napsa') ?? 0);
        $healthInsurance = (float) ($get('health_insurance') ?? 0);

        // Calculate 60% of basic pay
        $sixtyPercentBasicPay = $basicPay * 0.60;

        // Sum of income components (including other allowances)
        $totalIncome = $sixtyPercentBasicPay + $totalAllowances;

        // Sum of statutory deductions
        $statutoryDeductions = $paye + $pension + $healthInsurance;

        // Sum of other recurring deductions
        $otherRecurring = self::calculateTotalFromRepeater($get('other_recurring_deductions'));

        // Total deductions
        $totalDeductions = $statutoryDeductions + $otherRecurring;

        // Calculate net pay (can be negative if deductions exceed income)
        $netPay = $totalIncome - $totalDeductions;

        return round($netPay, 2);
    }

    /**
     * Update qualification status based on comparison of calculated vs actual net pay
     */
    protected static function updateQualificationStatus($actualNetPay, $calculatedNetPay, Set $set): void
    {
        if (empty($actualNetPay) || empty($calculatedNetPay)) {
            $set('qualification_status', 'review_required');
            return;
        }

        $actual = (float) $actualNetPay;
        $calculated = (float) $calculatedNetPay;

        // Calculate percentage variance
        $variance = abs($actual - $calculated);
        $percentageVariance = $calculated > 0 ? ($variance / $calculated) * 100 : 0;

        if ($percentageVariance < 5) {
            // Less than 5% variance - qualified
            $set('qualification_status', 'qualified');
        } elseif ($percentageVariance >= 5 && $percentageVariance < 15) {
            // Greater than or equal to 5% and less than 15% variance - review required
            $set('qualification_status', 'review_required');
        } else {
            // Greater than or equal to 15% variance - not qualified
            $set('qualification_status', 'not_qualified');
        }
    }

    /**
     * Recalculate civil service net pay, sync totals, and update qualification status.
     */
    protected static function recalculateCivilServiceNetPay(Set $set, Get $get, ?float $overrideAllowances = null): void
    {
        // Recalculate includes other allowances automatically in calculateNetPay
        $netPay = self::calculateNetPay($get, $overrideAllowances);
        $set('calculated_net_pay', $netPay);
    }

    /**
     * Recalculate New PMEC Eligibility based on formula from "ELIGILITY CALCULATOR plus (2).csv":
     * Maximum Allowable EMI = 60% of Monthly Pay
     * Eligible EMI = Maximum Allowable EMI - Existing Loans EMI
     * Loan Amount Eligibility = Based on Eligible EMI and loan period
     */
    protected static function recalculateNewPMECEligibility(Set $set, Get $get): void
    {
        $monthlyPay = (float) ($get('monthly_pay') ?? 0);
        $existingLoansEMI = (float) ($get('existing_loans_emi') ?? 0);
        
        // Calculate 60% of monthly pay
        $maximumAllowableEMI = $monthlyPay * 0.60;
        $set('maximum_allowable_emi', round($maximumAllowableEMI, 2));
        
        // Eligible EMI = Maximum Allowable EMI - Existing Loans EMI
        $eligibleEMI = $maximumAllowableEMI - $existingLoansEMI;
        $set('eligible_emi', round($eligibleEMI, 2));
        
        // Calculate Loan Amount Eligibility based on Eligible EMI and loan period
        $period = (int) ($get('loan_period') ?? 24);
        $monthlyInterestRate = (float) ($get('eligibility_interest_rate') ?? 4.0);
        
        if ($eligibleEMI > 0 && $period > 0 && $monthlyInterestRate > 0) {
            // Use the selected interest rate from loan types
            $totalInterestRate = $monthlyInterestRate * $period;
            
            // Formula: Loan Amount = (Eligible EMI * Period) / (1 + (Total Interest Rate / 100))
            // This calculates the maximum loan amount that can be serviced by the Eligible EMI
            $denominator = 1 + ($totalInterestRate / 100);
            $loanAmountEligibility = ($eligibleEMI * $period) / $denominator;
            $set('loan_amount_eligibility', round($loanAmountEligibility, 2));
        } else {
            $set('loan_amount_eligibility', 0);
        }
        
        // Auto-determine qualification status after eligibility calculation
        self::determineQualificationStatus($set, $get);
    }

    /**
     * Automatically determine qualification status based on eligibility data
     * Rules:
     * - Qualified: Principal amount <= Loan amount eligibility AND Eligible EMI > 0 AND all required data present
     * - Not Qualified: Principal amount > Loan amount eligibility OR Eligible EMI <= 0
     * - Review Required: Missing required data or edge cases
     */
    protected static function determineQualificationStatus(Set $set, Get $get): void
    {
        $principalAmount = (float) ($get('principal_amount') ?? 0);
        $loanAmountEligibility = (float) ($get('loan_amount_eligibility') ?? 0);
        $eligibleEMI = (float) ($get('eligible_emi') ?? 0);
        $monthlyPay = (float) ($get('monthly_pay') ?? 0);
        $loanTypeId = $get('loan_type_id');
        $borrowerId = $get('borrower_id');
        
        // Check if required data is missing
        $hasRequiredData = $loanTypeId && $borrowerId && $monthlyPay > 0 && $principalAmount > 0;
        
        // If required data is missing, set to review required
        if (!$hasRequiredData) {
            $set('qualification_status', 'review_required');
            return;
        }
        
        // If eligible EMI is negative or zero, not qualified
        if ($eligibleEMI <= 0) {
            $set('qualification_status', 'not_qualified');
            return;
        }
        
        // If loan amount eligibility is zero or not calculated, review required
        if ($loanAmountEligibility <= 0) {
            $set('qualification_status', 'review_required');
            return;
        }
        
        // Calculate percentage of eligibility used
        $eligibilityPercentage = ($principalAmount / $loanAmountEligibility) * 100;
        
        // Determine qualification based on principal amount vs eligibility
        if ($principalAmount <= $loanAmountEligibility) {
            // Within eligibility - qualified
            // Allow up to 100% of eligibility
            if ($eligibilityPercentage <= 100) {
                $set('qualification_status', 'qualified');
            } else {
                // Slightly over (up to 105%) - review required
                $set('qualification_status', 'review_required');
            }
        } else {
            // Exceeds eligibility - not qualified
            // If over 105% of eligibility, definitely not qualified
            if ($eligibilityPercentage > 105) {
                $set('qualification_status', 'not_qualified');
            } else {
                // Between 100-105% - review required
                $set('qualification_status', 'review_required');
            }
        }
    }

    /**
     * Helper to sum amounts from repeater state arrays.
     */
    protected static function calculateTotalFromRepeater(?array $items): float
    {
        return collect($items ?? [])->sum(fn ($item) => (float) ($item['amount'] ?? 0));
    }
}
