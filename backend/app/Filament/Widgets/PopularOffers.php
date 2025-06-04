<?php

namespace App\Filament\Widgets;

use App\Models\Offer;
use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PopularOffers extends ChartWidget
{
    protected static ?string $heading = 'Most Popular Offers';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $clinicId = Auth::id();
        
        $popularOffers = Offer::where('clinic_id', $clinicId)
            ->select('offers.title', DB::raw('COUNT(bookings.id) as booking_count'))
            ->leftJoin('bookings', 'offers.id', '=', 'bookings.offer_id')
            ->groupBy('offers.id', 'offers.title')
            ->having('booking_count', '>', 0)
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get();
            
        $labels = $popularOffers->pluck('title')->toArray();
        $data = $popularOffers->pluck('booking_count')->toArray();
        
        // If we have less than 5 offers with bookings, add placeholders
        if (count($labels) < 5) {
            $placeholdersNeeded = 5 - count($labels);
            for ($i = 0; $i < $placeholdersNeeded; $i++) {
                $labels[] = 'No data';
                $data[] = 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(54, 162, 235)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 159, 64)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 99, 132)',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
