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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;

class TimeRecordResource extends Resource
{
    protected static ?string $model = TimeRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        $query = static::getModel()::query();

        if (Auth::user()->is_admin) {
            return $query;
        } else {
            return $query->where('user_id', Auth::id());
        }
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
                Tables\Columns\TextColumn::make('user.name')->label('Empleado')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('check_in')->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('check_out')->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('approved_at')->label('Approved')
                    ->icon(fn(?string $state): string => match (true) {
                        $state === null => 'heroicon-o-x-mark',
                        default => 'heroicon-o-check-badge',
                    })
                // ->icon(function (?string $state): string {
                //     if ($state === null) {
                //         return 'heroicon-o-x-mark';
                //     } else {
                //         return 'heroicon-o-check-badge';
                //     }
                // }),
                ,
                Tables\Columns\TextColumn::make('ip')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter::make('approved_at')
                //     ->query(fn(Builder $query): Builder => $query->where('approved_at', '!=', null))
                //     ->toggle(),
                TernaryFilter::make('approved_at')
                    ->label('Is approved')
                    ->placeholder('All time records')
                    ->trueLabel('Approved')
                    ->falseLabel('Not approved')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull('approved_at'),
                        false: fn(Builder $query) => $query->whereNull('approved_at'),
                        blank: fn(Builder $query) => $query
                    )
            ])
            ->actions([
                Action::make('Approve')
                    ->action(fn(TimeRecord $record) => $record->update(['approved_at' => now()]))
                    ->requiresConfirmation()
                    ->visible(Auth::user()->is_admin)
            ])

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
