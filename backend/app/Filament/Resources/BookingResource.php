<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('offer_id')
                    ->relationship('offer', 'title', function (Builder $query) {
                        return $query->where('clinic_id', Auth::id());
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive(), // Make this field reactive to update validation
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\DatePicker::make('booking_date')
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Clear booking time when date changes to force re-validation
                        $set('booking_time', null);
                    })
                    ->reactive()
                    ->rules([
                        function ($get, $set, $state) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $offerId = $get('offer_id');
                                if (!$offerId) return;
                                
                                $offer = \App\Models\Offer::find($offerId);
                                if (!$offer) return;
                                
                                // Check if offer is active
                                if (!$offer->is_active) {
                                    $fail("This offer is not active and cannot be booked.");
                                    return;
                                }
                              
                                
                                // Check if the selected day is in available_days
                                if ($offer->available_days) {
                                    $bookingDayOfWeek = date('l', strtotime($value)); // Get day name (Monday, Tuesday, etc.)
                                    $bookingDayOfWeek = strtolower($bookingDayOfWeek); // Convert to lowercase for comparison
                                    if($offer->available_days[$bookingDayOfWeek]) {
                                        if($offer->available_days[$bookingDayOfWeek]["available"] === false) {
                                            $fail("This offer is Not Active on {$bookingDayOfWeek}s.");
                                            return;
                                    }



                                    
                                }
                                else  {
                                        $fail("This offer is not available on {$bookingDayOfWeek}s.");
                                        return;
                                    }

                                }
                            };
                        }
                    ]),
                Forms\Components\TimePicker::make('booking_time')
                    ->required()
                    ->seconds(false)
                    ->rules([
                        function ($get, $set, $state) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $offerId = $get('offer_id');
                                $bookingDate = $get('booking_date');
                                
                               
                                if (!$offerId || !$bookingDate || !$value) return;
                                
                                $offer = \App\Models\Offer::find($offerId);
                                if (!$offer) return;
                                
                                // Get day of week from booking_date, not from the time value
                                $bookingDayOfWeek = strtolower(date('l', strtotime($bookingDate)));
                              
                                // Check if offer is active
                                if (!$offer->is_active) {
                                    $fail("This offer is not active and cannot be booked.");
                                    return;
                                }
                                
                                // Check if the selected day is in available_days and has time constraints
                                $availableDays = $offer->available_days;
                                
                                if (!$availableDays || !isset($availableDays[$bookingDayOfWeek])) {
                                    $fail("This offer is not available on {$bookingDayOfWeek}s.");
                                    return;
                                }
                                
                                // Check if the day is available and has time constraints
                                $daySettings = $availableDays[$bookingDayOfWeek];
                                
                                if (!isset($daySettings['available']) || $daySettings['available'] === false) {
                                    $fail("This offer is not available on {$bookingDayOfWeek}s.");
                                    return;
                                }
                                
                                // Check time constraints if they exist
                                if (isset($daySettings['start_time']) && isset($daySettings['end_time'])) {
                                    $bookingTime = date('H:i', strtotime($value));
                                    
                                    if ($bookingTime < $daySettings['start_time'] || $bookingTime > $daySettings['end_time']) {
                                        $fail("Booking time must be between {$daySettings['start_time']} and {$daySettings['end_time']} on {$bookingDayOfWeek}s.");
                                        return;
                                    }
                                }
                            };
                        }
                    ]),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Select::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'refunded' => 'Refunded',
                    ])
                    ->required()
                    ->default('unpaid'),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Booking Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Booking ID')
                            ->badge()
                            ->color('primary')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Medium),
                            
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Booking Created')
                            ->dateTime('M d, Y H:i')
                            ->icon('heroicon-o-clock'),
                            
                        Infolists\Components\Grid::make(['default' => 2])
                            ->schema([
                                Infolists\Components\TextEntry::make('booking_date')
                                    ->label('Date')
                                    ->date('F j, Y')
                                    ->weight(FontWeight::Bold)
                                    ->icon('heroicon-o-calendar'),
                                    
                                Infolists\Components\TextEntry::make('booking_time')
                                    ->label('Time')
                                    ->time('g:i A')
                                    ->weight(FontWeight::Bold)
                                    ->icon('heroicon-o-clock'),
                                    
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'confirmed' => 'success',
                                        'pending' => 'warning',
                                        'cancelled' => 'danger',
                                        'completed' => 'info',
                                        default => 'gray',
                                    }),
                                    
                                Infolists\Components\TextEntry::make('payment_status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'paid' => 'success',
                                        'unpaid' => 'danger',
                                        'refunded' => 'warning',
                                        default => 'gray',
                                    }),
                            ]),
                            
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Notes')
                            ->default('No notes provided')
                            ->columnSpanFull(),
                    ]),
                    
                Infolists\Components\Section::make('Offer Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('offer.title')
                            ->label('Service')
                            ->weight(FontWeight::Bold)
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                            
                        Infolists\Components\TextEntry::make('offer.description')
                            ->label('Description')
                            ->markdown()
                            ->default('No description available')
                            ->columnSpanFull(),
                            
                        Infolists\Components\TextEntry::make('offer.price')
                            ->label('Price')
                            ->money('USD')
                            ->color('success'),
                    ]),
                    
                Infolists\Components\Section::make('Client Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('client.name')
                            ->label('Name')
                            ->icon('heroicon-o-user')
                            ->weight(FontWeight::Medium),
                            
                        Infolists\Components\Grid::make(['default' => 2])
                            ->schema([
                                Infolists\Components\TextEntry::make('client.email')
                                    ->label('Email')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->copyMessage('Email copied!')
                                    ->copyMessageDuration(1500),
                                    
                                Infolists\Components\TextEntry::make('client.phone')
                                    ->label('Phone')
                                    ->icon('heroicon-o-phone')
                                    ->default('No phone number')
                                    ->copyable()
                                    ->copyMessage('Phone copied!')
                                    ->copyMessageDuration(1500),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('offer.title')
                    ->label('Service')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('booking_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        'completed' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'refunded' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\Filter::make('booking_date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from'),
                        Forms\Components\DatePicker::make('date_to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->iconButton(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('confirm')
                        ->label('Confirm Bookings')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Collection $records): void {
                            $records->each(function (Booking $record): void {
                                $record->status = 'confirmed';
                                $record->save();
                            });
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('offer', function (Builder $query) {
                $query->where('clinic_id', Auth::id());
            });
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
            'index' => Pages\ListBookings::route('/'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
