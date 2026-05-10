<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Closure;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    // Step 1: Basic Information
                    Step::make('Basic Information')
                        ->icon('heroicon-o-information-circle')
                        ->description('Enter the basic event details')
                        ->schema([
                            TextInput::make('title')
                                ->label('Event Title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, Closure $set) {
                                    if ($operation === 'create') {
                                        $set('slug', Str::slug($state));
                                    }
                                })
                                ->placeholder('e.g., Gor Mahia vs AFC Leopards - Mashemeji Derby')
                                ->columnSpanFull(),
                            
                            TextInput::make('slug')
                                ->label('URL Slug')
                                ->required()
                                ->maxLength(255)
                                ->unique('events', 'slug', ignoreRecord: true)
                                ->helperText('This will be used in the event URL')
                                ->columnSpanFull(),
                            
                            Select::make('type')
                                ->label('Event Type')
                                ->options([
                                    'football' => 'Football Match (Sportpesa Premier League)',
                                    'concert' => 'Concert (International Artist)',
                                    'other' => 'Other Event',
                                ])
                                ->required()
                                ->native(false)
                                ->searchable()
                                ->columnSpanFull(),
                            
                            Select::make('venue_id')
                                ->label('Venue')
                                ->relationship('venue', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->helperText('Select an existing venue')
                                ->columnSpanFull(),
                            
                            RichEditor::make('description')
                                ->label('Event Description')
                                ->required()
                                ->maxLength(5000)
                                ->toolbarButtons([
                                    'bold',
                                    'italic',
                                    'underline',
                                    'bulletList',
                                    'orderedList',
                                    'link',
                                ])
                                ->placeholder('Describe the event in detail...')
                                ->columnSpanFull(),
                            
                            FileUpload::make('image')
                                ->label('Event Image')
                                ->image()
                                ->imageEditor()
                                ->directory('events')
                                ->visibility('public')
                                ->maxSize(5120)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->helperText('Upload a high-quality image (max 5MB)')
                                ->imageResizeTargetWidth('800')
                                ->imageResizeTargetHeight('400')
                                ->columnSpanFull(),
                        ]),
                    
                    // Step 2: Date & Time
                    Step::make('Date & Time')
                        ->icon('heroicon-o-calendar')
                        ->description('Set the event schedule')
                        ->schema([
                            DateTimePicker::make('start_date')
                                ->label('Start Date & Time')
                                ->required()
                                ->seconds(false)
                                ->minDate(fn ($record) => $record === null ? now() : null)
                                ->columnSpanFull(),
                            
                            DateTimePicker::make('end_date')
                                ->label('End Date & Time')
                                ->required()
                                ->after('start_date')
                                ->seconds(false)
                                ->columnSpanFull(),
                        ]),
                    
                    // Step 3: Ticket Tiers & Pricing
                    Step::make('Ticket Tiers')
                        ->icon('heroicon-o-ticket')
                        ->description('Configure ticket categories and pricing')
                        ->schema([
                            TextInput::make('base_price')
                                ->label('Base Price (KES)')
                                ->numeric()
                                ->prefix('KES')
                                ->minValue(0)
                                ->step(50)
                                ->helperText('Minimum ticket price for the event')
                                ->columnSpanFull(),
                            
                            Repeater::make('ticket_tiers')
                                ->label('Ticket Tiers')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Tier Name')
                                        ->required()
                                        ->placeholder('e.g., VIP, VVIP, Regular')
                                        ->maxLength(50),
                                    
                                    TextInput::make('price')
                                        ->label('Price (KES)')
                                        ->numeric()
                                        ->required()
                                        ->prefix('KES')
                                        ->minValue(0)
                                        ->step(50),
                                    
                                    TextInput::make('quantity')
                                        ->label('Available Quantity')
                                        ->numeric()
                                        ->required()
                                        ->minValue(1)
                                        ->step(1),
                                ])
                                ->columns(3)
                                ->defaultItems(1)
                                ->maxItems(5)
                                ->addActionLabel('Add Another Ticket Tier')
                                ->reorderable()
                                ->collapsible()
                                ->cloneable()
                                ->columnSpanFull(),
                        ]),
                    
                    // Step 4: Inventory Management
                    Step::make('Inventory')
                        ->icon('heroicon-o-chart-bar')
                        ->description('Manage ticket inventory')
                        ->schema([
                            TextInput::make('total_tickets')
                                ->label('Total Tickets')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->live()
                                ->helperText('Total number of tickets available for this event')
                                ->columnSpanFull(),
                            
                            TextInput::make('tickets_available')
                                ->label('Available Tickets')
                                ->numeric()
                                ->required()
                                ->minValue(0)
                                ->maxValue(fn ($get) => $get('total_tickets') ?? 0)
                                ->helperText('Number of tickets currently available for sale')
                                ->columnSpanFull(),
                        ]),
                    
                    // Step 5: Status & Visibility
                    Step::make('Status')
                        ->icon('heroicon-o-globe-alt')
                        ->description('Control event visibility')
                        ->schema([
                            Select::make('status')
                                ->label('Event Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Published',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->required()
                                ->default('draft')
                                ->native(false)
                                ->columnSpanFull(),
                            
                            Toggle::make('featured')
                                ->label('Feature this event')
                                ->helperText('Featured events appear prominently on the homepage')
                                ->default(false)
                                ->onColor('success')
                                ->offColor('gray')
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