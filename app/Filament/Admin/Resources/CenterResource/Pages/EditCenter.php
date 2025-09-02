<?php

namespace App\Filament\Admin\Resources\CenterResource\Pages;

use App\Filament\Admin\Resources\CenterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCenter extends EditRecord
{
    protected static string $resource = CenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
