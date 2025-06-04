<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Offer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $clinicId = Auth::id();
        
        $totalOffers = Offer::where('clinic_id', $clinicId)->count();
        $activeOffers = Offer::where('clinic_id', $clinicId)->where('is_active', true)->count();
        
        $totalBookings = Booking::whereHas('offer', function ($query) use ($clinicId) {
            $query->where('clinic_id', $clinicId);
        })->count();
        
        $pendingBookings = Booking::whereHas('offer', function ($query) use ($clinicId) {
            $query->where('clinic_id', $clinicId);
        })->where('status', 'pending')->count();
        
        $confirmedBookings = Booking::whereHas('offer', function ($query) use ($clinicId) {
            $query->where('clinic_id', $clinicId);
        })->where('status', 'confirmed')->count();
        
        $bookingsThisMonth = Booking::whereHas('offer', function ($query) use ($clinicId) {
            $query->where('clinic_id', $clinicId);
        })->whereMonth('created_at', Carbon::now()->month)
          ->whereYear('created_at', Carbon::now()->year)
          ->count();
          
        $bookingsLastMonth = Booking::whereHas('offer', function ($query) use ($clinicId) {
            $query->where('clinic_id', $clinicId);
        })->whereMonth('created_at', Carbon::now()->subMonth()->month)
          ->whereYear('created_at', Carbon::now()->subMonth()->year)
          ->count();
          
        $percentChange = $bookingsLastMonth > 0 
            ? round((($bookingsThisMonth - $bookingsLastMonth) / $bookingsLastMonth) * 100) 
            : ($bookingsThisMonth > 0 ? 100 : 0);

        $revenue = Booking::whereHas('offer', function ($query) use ($clinicId) {
            $query->where('clinic_id', $clinicId);
        })->where('payment_status', 'paid')
          ->join('offers', 'bookings.offer_id', '=', 'offers.id')
          ->sum('offers.price');

        return [
            Stat::make('Total Offers', $totalOffers)
                ->description($activeOffers . ' active offers')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, $totalOffers]),

            Stat::make('Total Bookings', $totalBookings)
                ->description($pendingBookings . ' pending, ' . $confirmedBookings . ' confirmed')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary')
                ->chart([2, 5, 3, 6, 4, $totalBookings]),

            Stat::make('Bookings This Month', $bookingsThisMonth)
                ->description($percentChange . '% ' . ($percentChange >= 0 ? 'increase' : 'decrease'))
                ->descriptionIcon($percentChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($percentChange >= 0 ? 'success' : 'danger')
                ->chart([2, 4, 6, 8, 5, $bookingsThisMonth]),
                
            Stat::make('Total Revenue', '$' . number_format($revenue, 2))
                ->description('From paid bookings')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([3000, 4200, 3800, 5100, 4800, $revenue]),
        ];
    }
}
