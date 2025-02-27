<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon as CarbonCarbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class TestChartWidget extends ChartWidget
{

use InteractsWithPageFilters;

protected static ?string $heading = 'Test Chart';

protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {

        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];

        
        $data = Trend::model(User::class)
        ->between(
            start: $start ? Carbon::parse($start) : now()->subMonths(6),
            end: $end ? Carbon::parse($end) : now(),
        )
        ->perMonth()
        ->count();
        
        //dd($data);
        
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
