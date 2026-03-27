<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLogs as Activity;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Activity Log';
    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->filtersTriggerAction(fn ($action) => $action->button()->label('Filters'))
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime('d M Y H:i:s')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->default('System')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event')
                    ->label('Action')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default   => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn ($state) => class_basename($state ?? ''))
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Record ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('properties')
                    ->label('Changes')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $data = is_string($state) ? json_decode($state, true) : $state->toArray();
                        if (empty($data)) return '-';
                        $old = $data['old'] ?? [];
                        $new = $data['attributes'] ?? [];
                        if (empty($old) && empty($new)) return json_encode($data);
                        $changes = [];
                        foreach ($new as $key => $val) {
                            if (isset($old[$key]) && $old[$key] != $val) {
                                $changes[] = "{$key}: {$old[$key]} -> {$val}";
                            } elseif (!isset($old[$key])) {
                                $changes[] = "{$key}: {$val}";
                            }
                        }
                        return implode(' | ', $changes) ?: '-';
                    })
                    ->wrap()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ])
                    ->native(false),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Model')
                    ->options([
                        'App\\Models\\Member'     => 'Member',
                        'App\\Models\\Loan'       => 'Loan',
                        'App\\Models\\Repayments' => 'Repayment',
                        'App\\Models\\Saving'     => 'Saving',
                        'App\\Models\\Group'      => 'Group',
                        'App\\Models\\Transfer'   => 'Transfer',
                        'App\\Models\\Penalty'    => 'Penalty',
                        'App\\Models\\Product'    => 'Product',
                    ]),
                Tables\Filters\SelectFilter::make('causer_id')
                    ->label('Officer / User')
                    ->options(fn () => \App\Models\User::orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
