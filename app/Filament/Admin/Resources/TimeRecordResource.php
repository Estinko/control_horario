<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TimeRecordResource\Pages;
use App\Filament\Admin\Resources\TimeRecordResource\RelationManagers;
use App\Models\TimeRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;

class TimeRecordResource extends Resource
{
    protected static ?string $model = TimeRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        $query = static::getModel()::query();

        return $query->where('user_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->required(),
            Forms\Components\DateTimePicker::make('check_in'),
            Forms\Components\DateTimePicker::make('check_out'),
            Forms\Components\TextInput::make('ip'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Empleado'),
                Tables\Columns\TextColumn::make('check_in')->dateTime(),
                Tables\Columns\TextColumn::make('check_out')->dateTime(),
                Tables\Columns\TextColumn::make('ip'),
            ])
            ->filters([])
            ->actions([])

            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeRecords::route('/'),
            'create' => Pages\CreateTimeRecord::route('/create'),
            'edit' => Pages\EditTimeRecord::route('/{record}/edit'),
        ];
    }
}
