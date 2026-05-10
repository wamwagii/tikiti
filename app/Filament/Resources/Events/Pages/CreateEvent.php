<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
    
    // Set the maximum content width for the create page
    public function getMaxContentWidth(): string
    {
        return '7xl'; // Options: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl', 'full', 'screen-lg', 'screen-xl', 'screen-2xl'
    }
    
    // Optional: Also customize the header actions if needed
    protected function getHeaderActions(): array
    {
        return [
            // You can add custom actions here if needed
        ];
    }

    //Redirect to Event List after creating an event
    protected function getRedirectUrl(): string
    {        return $this->getResource()::getUrl('index');
    }
}