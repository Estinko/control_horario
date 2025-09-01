<?php


namespace App\Filament\Admin\Resources\TimeRecordResource\Pages;

use App\Filament\Admin\Resources\TimeRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\TimeRecord;
use Illuminate\Support\Facades\Auth;


class ListTimeRecords extends ListRecords
{
    protected static string $resource = TimeRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [


            Actions\Action::make('fichar')
                ->label('Fichar')
                ->color('success')
                ->hidden(Auth::user()->is_admin)
                ->action(function () {
                    $timeRecord = TimeRecord::query()->notCheckedOut()->where('user_id', Auth::id())->get();
                    if ($timeRecord->count() > 0) {
                        $timeRecord->first()->update([
                            'check_out' => now(),
                        ]);
                    } else {
                        TimeRecord::create([
                            'user_id' => Auth::id(),
                            'check_in' => now(),
                        ]);
                    }
                }),

            // Actions\Action::make('ficharSalida')
            //     ->label('Fichar Salida')
            //     ->color('danger')
            //     ->hidden(Auth::user()->is_admin)
            //     ->action(function () {
            //         $record = TimeRecord::where('user_id', Auth::id())
            //             ->whereNull('end_time')
            //             ->latest()
            //             ->first();

            //         if ($record) {
            //             $record->update([
            //                 'end_time' => now(),
            //             ]);
            //         }
            //     }
            // ),
        ];
    }
}
