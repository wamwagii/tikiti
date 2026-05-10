<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Order Information')
                        ->icon('heroicon-o-shopping-cart')
                        ->description('Enter the basic order details')
                        ->schema([
                            TextInput::make('order_number')
                                ->label('Order Number')
                                ->required()
                                ->unique('orders', 'order_number', ignoreRecord: true)
                                ->default('ORD-' . strtoupper(Str::random(8)))
                                ->columnSpanFull(),
                            
                            Select::make('user_id')
                                ->label('Customer')
                                ->relationship('user', 'name')
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
                            
                            Select::make('status')
                                ->label('Order Status')
                                ->options([
                                    'pending' => 'Pending',
                                    'paid' => 'Paid',
                                    'cancelled' => 'Cancelled',
                                    'refunded' => 'Refunded',
                                ])
                                ->required()
                                ->default('pending')
                                ->columnSpanFull(),
                        ]),
                    
                    Step::make('Payment Details')
                        ->icon('heroicon-o-credit-card')
                        ->description('Payment information')
                        ->schema([
                            TextInput::make('total_amount')
                                ->label('Total Amount (KES)')
                                ->required()
                                ->numeric()
                                ->prefix('KES')
                                ->minValue(0)
                                ->step(100)
                                ->columnSpanFull(),
                            
                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options([
                                    'paystack' => 'Paystack (Card/M-Pesa)',
                                    'mpesa' => 'M-Pesa Direct',
                                    'bank' => 'Bank Transfer',
                                    'cash' => 'Cash',
                                ])
                                ->columnSpanFull(),
                            
                            TextInput::make('payment_reference')
                                ->label('Payment Reference')
                                ->placeholder('Transaction ID or Reference')
                                ->columnSpanFull(),
                            
                            TextInput::make('mpesa_receipt')
                                ->label('M-Pesa Receipt Number')
                                ->placeholder('e.g., QK9X8Y7Z6W')
                                ->columnSpanFull(),
                            
                            Select::make('payment_status')
                                ->label('Payment Status')
                                ->options([
                                    'pending' => 'Pending',
                                    'success' => 'Success',
                                    'failed' => 'Failed',
                                    'refunded' => 'Refunded',
                                ])
                                ->default('pending')
                                ->columnSpanFull(),
                        ]),
                    
                    Step::make('Ticket Details')
                        ->icon('heroicon-o-ticket')
                        ->description('Ticket information')
                        ->schema([
                            Textarea::make('ticket_details')
                                ->label('Ticket Details')
                                ->placeholder("Ticket Type: VIP\nQuantity: 2\nPrice: 5000\nSeats: A12, A13")
                                ->rows(5)
                                ->columnSpanFull(),
                            
                            Textarea::make('attendee_details')
                                ->label('Attendee Details')
                                ->placeholder("Name: John Doe\nEmail: john@example.com\nPhone: +254700000000\nID: 12345678")
                                ->rows(5)
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