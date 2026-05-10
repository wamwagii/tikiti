<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order_id')
                    ->numeric(),
                TextEntry::make('event_id')
                    ->numeric(),
                TextEntry::make('ticket_number'),
                TextEntry::make('tier_name'),
                TextEntry::make('price')
                    ->money(),
                TextEntry::make('seat_number')
                    ->placeholder('-'),
                TextEntry::make('qr_code')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('attendee_details')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('used_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
