<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use App\Models\Group;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Member Management';
    protected static ?string $navigationLabel = 'Add New Member';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Group Assignment')
                ->schema([
                    Forms\Components\Select::make('group_id')
                        ->label('Group Name')
                        ->options(function () {
                            $user = auth()->user();
                            if ($user->hasRole('super_admin')) {
                                return Group::where('status', 'active')->pluck('name', 'id');
                            }
                            return Group::where('status', 'active')
                                ->where('assigned_officer', $user->id)
                                ->pluck('name', 'id');
                        })
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $group = Group::find($state);
                            if ($group) {
                                $user = \App\Models\User::find($group->assigned_officer);
                                $set('assigned_officer_name', $user?->name ?? 'Not Assigned');
                            } else {
                                $set('assigned_officer_name', '');
                            }
                        }),

                    Forms\Components\TextInput::make('assigned_officer_name')
                        ->label('Assigned Officer')
                        ->disabled()
                        ->dehydrated(false)
                        ->reactive(),
                ])->columns(2),

            Forms\Components\Section::make('Personal Information')
                ->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('middle_name')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('id_number')
                        ->label('ID Number')
                        ->required()
                        ->numeric()
                        ->minLength(8)
                        ->maxLength(8)
                        ->rules(['digits:8'])
                        ->unique(table: 'members', column: 'id_number', ignoreRecord: true)
                        ->validationMessages([
                            'unique' => 'This ID number is already registered in the system.',
                            'digits' => 'ID number must be exactly 8 digits.',
                        ]),
                    Forms\Components\Select::make('gender')
                        ->options([
                            'male' => 'Male',
                            'female' => 'Female',
                        ])
                        ->required(),

                    Forms\Components\DatePicker::make('dob')
                        ->label('Date of Birth')
                        ->required(),

                    Forms\Components\Select::make('marital_status')
                        ->options([
                            'single' => 'Single',
                            'married' => 'Married',
                            'divorced' => 'Divorced',
                            'widowed' => 'Widowed',
                        ])
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ])
                        ->required()
                        ->default('active'),
                ])->columns(2),

            Forms\Components\Section::make('Contact Information')
                ->schema([
                    Forms\Components\TextInput::make('mobile_no')
                        ->label('Mobile No')
                        ->required()
                        ->numeric()
                        ->minLength(10)
                        ->maxLength(10)
                        ->rules(['digits:10']),

                    Forms\Components\TextInput::make('physical_address')
                        ->label('Physical Address'),

                    Forms\Components\TextInput::make('village'),

                    Forms\Components\TextInput::make('nearest_market')
                        ->label('Nearest Market'),

                    Forms\Components\TextInput::make('town'),

                    Forms\Components\TextInput::make('county'),

                    Forms\Components\TextInput::make('sub_county')
                        ->label('Sub County'),

                    Forms\Components\TextInput::make('postal_code')
                        ->label('Postal Code'),
                ])->columns(2),

            Forms\Components\Section::make('Next of Kin')
                ->schema([
                    Forms\Components\TextInput::make('kin_name')
                        ->label('Full Name')
                        ->required(),

                    Forms\Components\TextInput::make('kin_mobile')
                        ->label('Mobile No')
                        ->required()
                        ->numeric()
                        ->minLength(10)
                        ->maxLength(10)
                        ->rules(['digits:10']),

                    Forms\Components\TextInput::make('kin_village')
                        ->label('Village'),

                    Forms\Components\TextInput::make('kin_county')
                        ->label('County'),

                    Forms\Components\TextInput::make('kin_town')
                        ->label('Town'),

                    Forms\Components\TextInput::make('kin_sub_location')
                        ->label('Sub Location'),

                    Forms\Components\TextInput::make('kin_sub_county')
                        ->label('Sub County'),

                    Forms\Components\DatePicker::make('kin_dob')
                        ->label('Date of Birth'),
                ])->columns(2),

            Forms\Components\Section::make('Business Information')
                ->schema([
                    Forms\Components\TextInput::make('business_name')
                        ->label('Business Name'),

                    Forms\Components\TextInput::make('business_address')
                        ->label('Physical Address'),

                    Forms\Components\TextInput::make('business_county')
                        ->label('County'),

                    Forms\Components\TextInput::make('business_town')
                        ->label('Town'),

                    Forms\Components\TextInput::make('business_sub_county')
                        ->label('Sub County'),

                    Forms\Components\TextInput::make('business_postal_code')
                        ->label('Postal Code'),
                ])->columns(2),

            Forms\Components\Section::make('Guarantors')
                ->schema([
                    Forms\Components\Fieldset::make('Guarantor 1')
                        ->schema([
                            Forms\Components\TextInput::make('guarantor1_name')
                                ->label('Full Name'),
                            Forms\Components\TextInput::make('guarantor1_mobile')
                                ->label('Mobile No')
                                // ->required()
                                ->numeric()
                                ->minLength(10)
                                ->maxLength(10)
                                ->rules(['digits:10']),
                            Forms\Components\TextInput::make('guarantor1_relationship')
                                ->label('Relationship'),
                        ])->columns(3),

                    Forms\Components\Fieldset::make('Guarantor 2')
                        ->schema([
                            Forms\Components\TextInput::make('guarantor2_name')
                                ->label('Full Name'),
                            Forms\Components\TextInput::make('guarantor2_mobile')
                                ->label('Mobile No')
                                // ->required()
                                ->numeric()
                                ->minLength(10)
                                ->maxLength(10)
                                ->rules(['digits:10']),
                            Forms\Components\TextInput::make('guarantor2_relationship')
                                ->label('Relationship'),
                        ])->columns(3),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_number')
                    ->label('ID Number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mobile_no')
                    ->label('Mobile'),

                Tables\Columns\TextColumn::make('groups.name')
                    ->label('Group')
                    ->badge(),

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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user();
        
        if ($user->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }

        $groupIds = \App\Models\Group::where('assigned_officer', $user->id)->pluck('id');
        
        return parent::getEloquentQuery()->whereIn('group_id', $groupIds);
    }
}