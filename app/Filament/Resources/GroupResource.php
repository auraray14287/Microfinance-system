<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Models\Group;
use App\Models\Member;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Member Management';
    protected static ?string $navigationLabel = 'Group Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Group Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Group Name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('registration_number')
                        ->label('Registration Number')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Auto-generated on save')
                        ->helperText('Registration number is generated automatically based on the date of registration.'),

                    Forms\Components\TextInput::make('location')
                        ->label('Physical Location')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('contact')
                        ->label('Contact')
                        ->maxLength(255),

                    Forms\Components\Select::make('assigned_officer')
                        ->label('Assigned Officer')
                        ->options(User::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ])
                        ->required()
                        ->default('active'),
                ])->columns(2),

            Forms\Components\Section::make('Leadership')
                ->description('Leadership must be selected from the group\'s registered members.')
                ->schema([
                    Forms\Components\Select::make('chairperson')
                        ->label('Chairperson')
                        ->options(fn (Get $get, ?Group $record) =>
                            $record
                                ? $record->members()->get()->pluck('full_name', 'id')
                                : collect())
                        ->searchable()
                        ->nullable(),

                    Forms\Components\Select::make('secretary')
                        ->label('Secretary')
                        ->options(fn (Get $get, ?Group $record) =>
                            $record
                                ? $record->members()->get()->pluck('full_name', 'id')
                                : collect())
                        ->searchable()
                        ->nullable(),

                    Forms\Components\Select::make('treasurer')
                        ->label('Treasurer')
                        ->options(fn (Get $get, ?Group $record) =>
                            $record
                                ? $record->members()->get()->pluck('full_name', 'id')
                                : collect())
                        ->searchable()
                        ->nullable(),
                ])->columns(3),

            Forms\Components\Section::make('Group Members')
                ->description('Members assigned to this group.')
                ->schema([
                    Forms\Components\Select::make('members')
                        ->label('Members')
                        ->multiple()
                        ->relationship('members', 'name')
                        ->searchable()
                        ->preload()
                        ->getOptionLabelFromRecordUsing(fn (Member $record) =>
                            "{$record->first_name} {$record->middle_name} {$record->last_name} - {$record->id_number}"
                        ),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Group Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('registration_number')
                    ->label('Reg No.')
                    ->searchable(),

                Tables\Columns\TextColumn::make('location')
                    ->searchable(),

                Tables\Columns\TextColumn::make('contact'),

                Tables\Columns\TextColumn::make('officer.name')
                    ->label('Assigned Officer'),

                Tables\Columns\TextColumn::make('members_count')
                    ->label('Members')
                    ->counts('members')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('report')
                    ->label('Report')
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('success')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('From Date')
                            ->native(false)
                            ->nullable(),
                        Forms\Components\DatePicker::make('date_to')
                            ->label('To Date')
                            ->native(false)
                            ->default(now())
                            ->nullable(),
                        Forms\Components\Select::make('action')
                            ->label('Output')
                            ->options([
                                'preview'  => 'Preview in browser',
                                'download' => 'Download PDF',
                            ])
                            ->default('download')
                            ->required(),
                    ])
                    ->action(function (array $data, $record) {
                        $params = http_build_query([
                            'date_from' => $data['date_from'] ?? '',
                            'date_to'   => $data['date_to']   ?? '',
                        ]);

                        $route = $data['action'] === 'preview'
                            ? route('group.report.preview',  ['id' => $record->id]) . '?' . $params
                            : route('group.report.download', ['id' => $record->id]) . '?' . $params;

                        return redirect($route);
                    })
                    ->modalHeading('Generate Group Report')
                    ->modalDescription('Select a date range to filter the report. Leave blank for all-time data.')
                    ->modalSubmitActionLabel('Generate Report'),

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
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user();
        if ($user->hasRole("super_admin")) {
            return parent::getEloquentQuery();
        }
        return parent::getEloquentQuery()->where("assigned_officer", $user->id);
    }
}
