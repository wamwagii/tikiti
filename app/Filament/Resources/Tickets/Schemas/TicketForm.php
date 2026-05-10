<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Ticket Information')
                        ->icon('heroicon-o-ticket')
                        ->description('Enter the ticket details')
                        ->schema([
                            Select::make('order_id')
                                ->label('Order')
                                ->relationship('order', 'order_number')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->columnSpanFull(),
                            
                            Select::make('event_id')
                                ->label('Event')
                                ->relationship('event', 'title')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->columnSpanFull(),
                            
                            TextInput::make('ticket_number')
                                ->label('Ticket Number')
                                ->required()
                                ->unique('tickets', 'ticket_number', ignoreRecord: true)
                                ->helperText('Unique ticket identifier')
                                ->columnSpanFull(),
                            
                            TextInput::make('tier_name')
                                ->label('Ticket Tier')
                                ->required()
                                ->placeholder('e.g., VIP, VVIP, Regular, Terrace')
                                ->columnSpanFull(),
                            
                            TextInput::make('price')
                                ->label('Price (KES)')
                                ->required()
                                ->numeric()
                                ->prefix('KES')
                                ->minValue(0)
                                ->step(50)
                                ->columnSpanFull(),
                            
                            TextInput::make('seat_number')
                                ->label('Seat Number')
                                ->placeholder('e.g., Row A, Seat 12')
                                ->columnSpanFull(),
                            
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'available' => 'Available',
                                    'sold' => 'Sold',
                                    'used' => 'Used',
                                    'refunded' => 'Refunded',
                                ])
                                ->required()
                                ->default('available')
                                ->columnSpanFull(),
                        ]),
                    
                    Step::make('Attendee Details')
                        ->icon('heroicon-o-user')
                        ->description('Information about the ticket holder')
                        ->schema([
                            Textarea::make('attendee_details')
                                ->label('Attendee Information')
                                ->placeholder("Name:\nEmail:\nPhone:\nID Number:")
                                ->rows(5)
                                ->columnSpanFull(),
                        ]),
                    
                    Step::make('Additional Info')
                        ->icon('heroicon-o-qr-code')
                        ->description('QR code and usage tracking')
                        ->schema([
                            TextInput::make('qr_code')
                                ->label('QR Code')
                                ->placeholder('QR code data or URL')
                                ->columnSpanFull(),
                            
                            DateTimePicker::make('used_at')
                                ->label('Used At')
                                ->helperText('When the ticket was scanned/used')
                                ->columnSpanFull(),
                        ]),
                ])
                ->skippable()
                ->persistStepInQueryString()
                ->columnSpanFull()
                ->extraAttributes(['class' => 'w-full']),
            ])
            ->extraAttributes(['class' => 'space-y-6']);
    }
}