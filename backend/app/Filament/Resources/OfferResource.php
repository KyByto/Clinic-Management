<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfferResource\Pages;
use App\Models\Offer;
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

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday', 
            'thursday' => 'Thursday', 
            'friday' => 'Friday', 
            'saturday' => 'Saturday', 
            'sunday' => 'Sunday'
        ];
        
        $dayComponents = [];
        
        foreach ($days as $dayKey => $dayName) {
            $dayComponents[] = Forms\Components\Section::make($dayName)
                ->schema([
                    Forms\Components\Checkbox::make("available_days.{$dayKey}.available")
                        ->label("Available on {$dayName}")
                        ->live(),
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TimePicker::make("available_days.{$dayKey}.start_time")
                                ->label('Start Time')
                                ->seconds(false)
                                ->required()
                                ->hidden(fn (Forms\Get $get): bool => !$get("available_days.{$dayKey}.available")),
                            Forms\Components\TimePicker::make("available_days.{$dayKey}.end_time")
                                ->label('End Time')
                                ->seconds(false)
                                ->required()
                                ->hidden(fn (Forms\Get $get): bool => !$get("available_days.{$dayKey}.available")),
                        ])
                ])
                ->collapsible();
        }

        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\FileUpload::make('image_path')
                    ->image()
                    ->directory('offer-images'),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true),
                Forms\Components\Section::make('Availability')
                    ->schema($dayComponents)
                    ->collapsible(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\ImageEntry::make('image_path')
                            ->label('')
                            ->visibility('public')
                            ->alignCenter()
                            ->height(200)
                            ->columnSpanFull(),
                    ])
                    ->hidden(fn ($record) => empty($record->image_path)),

                Infolists\Components\Section::make('Offer Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label('Title')
                            ->weight(FontWeight::Bold)
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                            
                        Infolists\Components\TextEntry::make('description')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),
                            
                        Infolists\Components\Grid::make(['default' => 2])
                            ->schema([
                                Infolists\Components\TextEntry::make('price')
                                    ->label('Price')
                                    ->money('USD')
                                    ->color('success')
                                    ->weight(FontWeight::Bold),
                                    
                                Infolists\Components\IconEntry::make('is_active')
                                    ->label('Status')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('danger'),
                            ]),
                    ])
                    ->columns(1),
                    
                Infolists\Components\Section::make('Availability')
                    ->schema([
                        Infolists\Components\TextEntry::make('available_days_formatted')
                            ->label('')
                            ->state(function (Offer $record): string {
                                $state = $record->available_days;
                                
                                if (!is_array($state)) return 'No availability information';
                                
                                $days = [
                                    'monday' => 'Monday',
                                    'tuesday' => 'Tuesday',
                                    'wednesday' => 'Wednesday',
                                    'thursday' => 'Thursday',
                                    'friday' => 'Friday',
                                    'saturday' => 'Saturday',
                                    'sunday' => 'Sunday',
                                ];
                                
                                $output = '';
                                
                                foreach ($days as $dayKey => $dayName) {
                                    if (isset($state[$dayKey]['available']) && $state[$dayKey]['available']) {
                                        $startTime = $state[$dayKey]['start_time'] ?? '';
                                        $endTime = $state[$dayKey]['end_time'] ?? '';
                                        
                                        $output .= "<div class='mb-1 flex justify-between'>";
                                        $output .= "<span class='font-medium text-gray-700'>{$dayName}</span>";
                                        $output .= "<span class='text-gray-600'>{$startTime} - {$endTime}</span>";
                                        $output .= "</div>";
                                    }
                                }
                                
                                return empty($output) ? 'No available days' : $output;
                            })
                            ->html(),
                    ]),
                    
                Infolists\Components\Section::make('Statistics')
                    ->schema([
                        Infolists\Components\Grid::make(['default' => 2])
                            ->schema([
                                Infolists\Components\TextEntry::make('bookings_count')
                                    ->label('Total Bookings')
                                    ->state(function (Offer $record) {
                                        return $record->bookings()->count();
                                    })
                                    ->color('primary')
                                    ->icon('heroicon-o-calendar')
                                    ->iconColor('primary'),
                                    
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Created')
                                    ->dateTime('M d, Y')
                                    ->icon('heroicon-o-clock'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
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
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('clinic_id', Auth::id());
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
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
