<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenaltyResource\Pages;
use App\Models\Penalty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PenaltyResource extends Resource
{
    protected static ?string $model = Penalty::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Loans';
    protected static ?string $navigationLabel = 'Penalties';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('loan_id')
                ->label('Loan')
                ->relationship('loan', 'id')
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('amount')
                ->required()
                ->numeric()
                ->prefix('KES'),

            Forms\Components\TextInput::make('reason')
                ->nullable()
                ->maxLength(255),

            Forms\Components\DatePicker::make('penalty_date')
                ->required()
                ->default(now()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('loan.id')
                    ->label('Loan ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('KES')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reason'),

                Tables\Columns\TextColumn::make('penalty_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenalties::route('/'),
            'create' => Pages\CreatePenalty::route('/create'),
            'edit' => Pages\EditPenalty::route('/{record}/edit'),
        ];
    }
}