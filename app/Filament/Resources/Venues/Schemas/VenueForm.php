<?php

namespace App\Filament\Resources\Venues\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class VenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Basic Information')
                        ->icon('heroicon-o-building-library')
                        ->description('Enter the basic venue details')
                        ->schema([
                            TextInput::make('name')
                                ->label('Venue Name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g., Kasarani Stadium, Nyayo Stadium')
                                ->columnSpanFull(),
                            
                            TextInput::make('location')
                                ->label('Location/Area')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g., Kasarani, Industrial Area')
                                ->columnSpanFull(),
                            
                            TextInput::make('city')
                                ->label('City')
                                ->required()
                                ->default('Nairobi')
                                ->maxLength(100)
                                ->columnSpanFull(),
                            
                            TextInput::make('address')
                                ->label('Full Address')
                                ->maxLength(500)
                                ->placeholder('e.g., Thika Road, Nairobi')
                                ->columnSpanFull(),
                            
                            TextInput::make('capacity')
                                ->label('Seating Capacity')
                                ->numeric()
                                ->minValue(0)
                                ->step(1000)
                                ->helperText('Total number of seats available')
                                ->columnSpanFull(),
                        ]),
                    
                    Step::make('Additional Information')
                        ->icon('heroicon-o-information-circle')
                        ->description('Add extra details about the venue')
                        ->schema([
                            TextInput::make('description')
                                ->label('Description')
                                ->maxLength(1000)
                                ->placeholder('Brief description of the venue...')
                                ->columnSpanFull(),
                            
                            FileUpload::make('image')
                                ->label('Venue Image')
                                ->image()
                                ->imageEditor()
                                ->directory('venues')
                                ->maxSize(5120)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->helperText('Upload a photo of the venue (max 5MB)')
                                ->imageResizeTargetWidth('800')
                                ->imageResizeTargetHeight('400')
                                ->columnSpanFull(),
                        ]),
                    
                    Step::make('Amenities')
                        ->icon('heroicon-o-wifi')
                        ->description('List the amenities available at this venue')
                        ->schema([
                            Repeater::make('amenities')
                                ->label('Amenities')
                                ->simple(  // Changed: Use simple() method
                                    TextInput::make('amenity')
                                        ->label('Amenity')
                                        ->required()
                                        ->placeholder('e.g., Parking, WiFi, VIP Lounge, Media Center')
                                        ->maxLength(100)
                                )
                                ->defaultItems(1)  // Changed from 3 to 1
                                ->maxItems(15)     // Increased max items
                                ->addActionLabel('Add Amenity')
                                ->reorderable()
                                ->reorderableWithButtons()  // Added for better UI
                                ->collapsible()
                                ->cloneable()
                                ->helperText('List all amenities available at this venue')
                                ->columnSpanFull(),
                        ]),
                    
                    Step::make('Contact Information')
                        ->icon('heroicon-o-phone')
                        ->description('Venue contact details')
                        ->schema([
                            TextInput::make('contact_info.phone')
                                ->label('Phone Number')
                                ->tel()
                                ->placeholder('e.g., +254 700 000 000')
                                ->columnSpanFull(),
                            
                            TextInput::make('contact_info.email')
                                ->label('Email Address')
                                ->email()
                                ->placeholder('e.g., info@venue.co.ke')
                                ->columnSpanFull(),
                            
                            TextInput::make('contact_info.website')
                                ->label('Website')
                                ->url()
                                ->placeholder('e.g., https://www.venue.co.ke')
                                ->columnSpanFull(),
                        ]),
                    
                    Step::make('Status')
                        ->icon('heroicon-o-globe-alt')
                        ->description('Control venue visibility')
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Active Venue')
                                ->helperText('Active venues will appear in event creation dropdown')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
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