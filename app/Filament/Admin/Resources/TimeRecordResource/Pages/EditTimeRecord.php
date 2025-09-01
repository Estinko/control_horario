<?php

namespace App\Filament\Admin\Resources\TimeRecordResource\Pages;

use App\Filament\Admin\Resources\TimeRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeRecord extends EditRecord
{
    protected static string $resource = TimeRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
