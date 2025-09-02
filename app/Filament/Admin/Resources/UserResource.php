<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Wizard\Step::make('Datos Usuario')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->reactive()
                                ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                    $words = explode(' ', $state);
                                    $initial = collect($words)->map(function (string $word) {
                                        return substr($word, 0, 1);
                                    })->join('');
                                    if (count($words) > 3) {
                                        dd($get('employee_key'), $initial);
                                    }
                                }),

                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\DateTimePicker::make('email_verified_at'),
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->revealable()
                                ->dehydrated(fn(?string $state): bool => filled($state))
                                ->required(fn(string $operation): bool => $operation === 'create')
                                ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                ->maxLength(255),
                            Forms\Components\Toggle::make('is_admin')
                                ->required(),
                        ]),
                    Wizard\Step::make('Datos Trabajador')
                        ->schema([
                            Forms\Components\TextInput::make('dni')
                                ->required()
                                ->maxLength(15)
                                ->reactive()
                                ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                    $dni = substr($state, 0, 4);
                                    $set('employee_key', $dni);
                                }),
                            Forms\Components\DatePicker::make('birthdate')
                                ->required()
                                ->maxDate(now()),
                            Forms\Components\TextInput::make('vacation_left')
                                ->required()
                                ->default(30)
                                ->readOnly()
                                ->minValue(0)
                                ->maxValue(30),
                            Forms\Components\TextInput::make('salary')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('employee_key')
                                ->required()
                                ->disabled(true)
                                ->maxLength(10)
                                ->reactive(),
                            Forms\Components\TextInput::make('bonus_job')
                                ->required()
                                ->numeric()
                                ->suffix('%')
                        ]),
                ]),

                // ->required(function ($record) {
                //     if (!$record) {
                //         return true;
                //     }
                //     return false;
                // })

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_admin')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Section::make('Infomación Personal')
                        ->schema([
                            TextEntry::make('name'),
                            TextEntry::make('email'),
                            IconEntry::make('is_admin'),

                        ])
                        ->columns(3),
                    Section::make()
                        ->schema([
                            TextEntry::make('created_at'),
                            TextEntry::make('updated_at'),
                        ])->grow(false)
                ])
                    ->from('md')
                    ->columnSpan('full')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TimeRecordsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
