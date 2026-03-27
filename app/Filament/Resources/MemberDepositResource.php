<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberDepositResource\Pages;
use App\Models\MemberDeposit;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MemberDepositResource extends Resource
{
    protected static ?string $model = MemberDeposit::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transactions';
    protected static ?string $navigationLabel = 'Deposit Transactions';
    protected static bool $shouldRegisterNavigation = false; // hidden from sidebar

    public static function getGloballySearchableAttributes(): array
    {
        return ['reference', 'contact', 'notes'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Member'   => $record->member ? trim("{$record->member->first_name} {$record->member->last_name}") : '-',
            'Type'     => ucfirst($record->type),
            'Amount'   => 'KES ' . number_format($record->amount, 2),
            'Balance'  => 'KES ' . number_format($record->balance_after, 2),
            'Date'     => $record->created_at?->format('d M Y') ?? '-',
            'Officer'  => $record->createdBy?->name ?? '-',
        ];
    }

    public static function getGlobalSearchResultUrl(\Illuminate\Database\Eloquent\Model $record): string
    {
        return route('filament.admin.pages.member-deposit-page');
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('member.first_name')->label('Member'),
            Tables\Columns\TextColumn::make('type'),
            Tables\Columns\TextColumn::make('amount')->money('KES'),
            Tables\Columns\TextColumn::make('reference'),
            Tables\Columns\TextColumn::make('created_at')->date(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberDeposits::route('/'),
        ];
    }
}
