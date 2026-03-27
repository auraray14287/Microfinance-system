<?php

namespace App\Filament\Resources;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Forms\Set;
use Filament\Forms\Get;
use App\Filament\Resources\RepaymentsResource\Pages;
use App\Filament\Resources\RepaymentsResource\RelationManagers;
use App\Models\Repayments;
use App\Models\Group;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Exports\RepaymentsExporter;
use Filament\Tables\Actions\ExportAction;

class RepaymentsResource extends Resource
{
    protected static ?string $model = Repayments::class;

    protected static ?string $navigationGroup = 'Transactions';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Pay Loan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'fas-dollar-sign';

    protected static ?string $recordTitleAttribute = 'transaction_code';

    public static function getGloballySearchableAttributes(): array
    {
        return ['transaction_code', 'contact', 'reference_number'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $log = \Spatie\Activitylog\Models\Activity::where('subject_type', 'App\\Models\\Repayments')
            ->where('subject_id', $record->id)
            ->where('event', 'created')
            ->first();
        $officer = $log?->causer?->name ?? '-';

        return [
            'Loan'    => $record->loan_number ?? '-',
            'Amount'  => 'KES ' . number_format($record->payments, 2),
            'Contact' => $record->contact ?? '-',
            'Date'    => $record->created_at?->format('d M Y') ?? '-',
            'Officer' => $officer,
        ];
    }

    public static function getGlobalSearchResultUrl(\Illuminate\Database\Eloquent\Model $record): string
    {
        return static::getUrl('view', ['record' => $record]);
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('loan_id')
                    ->label('Loan Number')
                    ->prefixIcon('heroicon-o-wallet')
                    ->options(function () {
                        $user = auth()->user();
                        if ($user->hasRole('super_admin')) {
                            return \App\Models\Loan::whereNotNull('loan_number')
                                ->pluck('loan_number', 'id');
                        }
                        $groupIds = \App\Models\Group::where('assigned_officer', $user->id)->pluck('id');
                        $memberIds = \App\Models\Member::whereIn('group_id', $groupIds)->pluck('id');
                        return \App\Models\Loan::whereIn('member_id', $memberIds)
                            ->whereNotNull('loan_number')
                            ->pluck('loan_number', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->live(onBlur: true)
                    ->required(function ($state, Set $set) {
                       
                        if ($state) {
                            $balance = \App\Models\Loan::findOrFail($state)->balance;
                            $set('balance', $balance);
                        }
                        return true;
                    }),



                Forms\Components\TextInput::make('payments')
                    ->label('Repayment Amount')
                    ->prefixIcon('fas-dollar-sign')
                    ->required(),
                Forms\Components\TextInput::make('balance')
                    ->label('Current Balance')
                    ->prefixIcon('fas-dollar-sign')
                    ->readOnly(),

                Forms\Components\Select::make('payments_method')
                    ->label('Payment Method')
                    ->prefixIcon('fas-dollar-sign')
                    ->required()
                    ->options([
                        'bank_transfer' => 'Bank Transfer',
                        'mobile_money' => 'Mobile Money',
                        'pemic' => 'PEMIC',
                        'cheque' => 'Cheque',
                        'cash' => 'Cash',

                    ]),
                Forms\Components\TextInput::make('transaction_code')
                    ->label('Transaction Code')
                    ->prefixIcon('heroicon-o-hashtag')
                    ->columnSpan(1),
                Forms\Components\TextInput::make('contact')
                    ->label('Contact Used to Send Money')
                    ->prefixIcon('heroicon-o-phone')
                    ->columnSpan(1),
                Forms\Components\TextInput::make('reference_number')
                    ->label('Transaction Reference')
                    ->prefixIcon('fas-dollar-sign')
                    ->columnSpan(2),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
           ->headerActions([
            ExportAction::make()
                ->exporter(RepaymentsExporter::class)
        ])
         ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                ->label('Payments Date')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('reference_number')
                    ->label('Reference Number')
                        ->searchable(),
                Tables\Columns\TextColumn::make('transaction_code')
                    ->label('Transaction Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact')
                    ->label('Contact')
                    ->searchable(),
                Tables\Columns\TextColumn::make('loan_number.loan_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('loan_number.loan_status')
                ->label('Loan Status')
                ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('payments')
                    ->searchable(),
                    // Tables\Columns\TextColumn::make('loan_number.repayment_amount')
                    // ->label('Total Repayments')
                    // ->searchable(),
                Tables\Columns\TextColumn::make('balance')
                    ->searchable(),
                   
                  
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payments_method')
                    ->options([
                        'bank_transfer' => 'Bank Transfer',
                        'mobile_money' => 'Mobile Money',
                        'pemic' => 'PEMIC',
                        'cheque' => 'Cheque',
                        'cash' => 'Cash',


                    ]),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user();
        if ($user->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }
        $groupIds = \App\Models\Group::where('assigned_officer', $user->id)->pluck('id');
        $memberIds = \App\Models\Member::whereIn('group_id', $groupIds)->pluck('id');
        $loanIds = \App\Models\Loan::whereIn('member_id', $memberIds)->pluck('id');
        return parent::getEloquentQuery()->whereIn('loan_id', $loanIds);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRepayments::route('/'),
            'create' => Pages\CreateRepayments::route('/create'),
            'view' => Pages\ViewRepayments::route('/{record}'),
            'edit' => Pages\EditRepayments::route('/{record}/edit'),
        ];
    }
}
