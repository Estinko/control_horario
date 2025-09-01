<?php

namespace App\Filament\Admin\Widgets;

use App\Models\TimeRecord;
use Filament\Widgets\ChartWidget;

class ApprovedTimeRecordChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $approved = TimeRecord::query()->where('approved_at', '!=', null)->count();
        $notApproved = TimeRecord::query()->where('approved_at', '=', null)->count();
        return [
            'datasets' => [
                [
                    'label' => 'Approved Records',
                    'data' => [$approved, $notApproved],
                ],
            ],
            'labels' => ['Approved', 'Not Approved'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
