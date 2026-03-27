<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class Expenses extends LineChartWidget
{
    use InteractsWithPageFilters;
    use HasWidgetShield;

    protected static ?int $sort = 3;

    // ── Hidden from dashboard ─────────────────────────────────────────────
    public static function canView(): bool
    {
        return false;
    }

    public function getHeading(): string
    {
        return 'Business Expenses';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [['label' => 'Business Expenses', 'data' => []]],
            'labels'   => [],
        ];
    }
}