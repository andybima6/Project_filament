<?php

namespace App\Filament\Widgets;

use App\Models\User;

use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon as SupportCarbon;

class Chart extends ChartWidget
{

    use InteractsWithPageFilters;
    protected static ?string $heading = 'Chart';
    protected int | string | array $columnSpan = 1;

    protected function getData(): array

    {
        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];

        // return [
        //     'datasets' => [
        //         [
        //             'label' => 'Blog posts created',
        //             'data' => [300,500,1000],
        //             'backgroundColor'=>[
        //                  'rgb(255,99,132)',
        //                  'rgb(54,162,235)',
        //                  'rgb(255,205,86)'
        //             ]
        //         ],
        //     ],
        //     'labels' => ["A","B","C"],
        // ];


// Flowframe pda decomentation
    $data = Trend::model(User::class)
    ->between(
        start: $start ? Carbon::parse($start) : now()->subMonths(6),
        end: $end ? Carbon::parse($end):now(),
    )
    ->perMonth()
    ->count();
   // Filters data berdasarkan tanggal
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' =>$data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        // return 'doughnut';
        return 'line';
    }
}
