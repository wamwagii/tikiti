<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    //Redirect to Ticket List after creating a ticket
    protected function getRedirectUrl(): string
    {        return $this->getResource()::getUrl('index');
    }
}
