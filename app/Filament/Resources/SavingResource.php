<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SavingResource\Pages;
use App\Models\Saving;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SavingResource extends Resource
{
    protected static ?string $model = Saving::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transactions';
    protected static ?string $navigationLabel = 'Update Savings';
    protected static ?int $navigationSort = 1;
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $recordTitleAttribute = 'transaction_code';

    public static function getGloballySearchableAttributes(): array
    {
        return ['transaction_code', 'contact'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        // Find officer from activity log
        $log = \Spatie\Activitylog\Models\Activity::where('subject_type', 'App\\Models\\Saving')
            ->where('subject_id', $record->id)
            ->where('event', 'created')
            ->first();
        $officer = $log?->causer?->name ?? '-';

        return [
            'Member'  => $record->member?->first_name . ' ' . $record->member?->last_name ?? '-',
            'Amount'  => 'KES ' . number_format($record->amount, 2),
            'Contact' => $record->contact ?? '-',
            'Date'    => $record->contribution_date ?? '-',
            'Officer' => $officer,
        ];
    }

    public static function getGlobalSearchResultUrl(\Illuminate\Database\Eloquent\Model $record): string
    {
        return static::getUrl('edit', ['record' => $record]);
    }
    
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('group_id')
                ->label('Group')
                ->required()
                ->searchable()
                ->preload()
                ->options(function () {
                    $user = auth()->user();
                    if ($user->hasRole('super_admin')) {
                        return \App\Models\Group::where('status', 'active')->pluck('name', 'id');
                    }
                    return \App\Models\Group::where('assigned_officer', auth()->id())
                        ->where('status', 'active')
                        ->pluck('name', 'id');
                })
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => $set('member_id', null)),

            Forms\Components\Select::make('member_id')
                ->label('Member')
                ->required()
                ->searchable()
                ->preload()
                ->reactive()
                ->options(function (Get $get) {
                    $groupId = $get('group_id');
                    $user = auth()->user();

                    if ($groupId) {
                        return \App\Models\Member::where('group_id', $groupId)->get()->mapWithKeys(fn ($m) =>
                            [$m->id => "{$m->first_name} {$m->middle_name} {$m->last_name} - {$m->id_number}"]
                        );
                    }

                    if ($user->hasRole('super_admin')) {
                        return \App\Models\Member::all()->mapWithKeys(fn ($m) =>
                            [$m->id => "{$m->first_name} {$m->middle_name} {$m->last_name} - {$m->id_number}"]
                        );
                    }
                    $groupIds = \App\Models\Group::where('assigned_officer', $user->id)->pluck('id');
                    return \App\Models\Member::whereIn('group_id', $groupIds)->get()->mapWithKeys(fn ($m) =>
                        [$m->id => "{$m->first_name} {$m->middle_name} {$m->last_name} - {$m->id_number}"]
                    );
                }),

            Forms\Components\TextInput::make('amount')
                ->required()
                ->numeric()
                ->prefix('KES'),

            Forms\Components\DatePicker::make('contribution_date')
                ->required()
                ->default(now()),

            Forms\Components\Textarea::make('notes')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('group.name')
                    ->label('Group')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('KES')
                    ->sortable(),

                Tables\Columns\TextColumn::make('contribution_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('notes')
                    ->limit(30),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->relationship('group', 'name'),
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
            'index' => Pages\ListSavings::route('/'),
            'create' => Pages\CreateSaving::route('/create'),
            'edit' => Pages\EditSaving::route('/{record}/edit'),
        ];
    }
}