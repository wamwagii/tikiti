<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('ticket_number')
                    ->label('Ticket #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                TextColumn::make('tier_name')
                    ->label('Tier')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'VVIP' => 'success',
                        'VIP' => 'warning',
                        'Regular' => 'info',
                        'Terrace' => 'gray',
                        default => 'secondary',
                    }),
                
                TextColumn::make('price')
                    ->label('Price')
                    ->money('KES')
                    ->sortable(),
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sold' => 'success',
                        'used' => 'warning',
                        'available' => 'info',
                        'refunded' => 'danger',
                        default => 'gray',
                    }),
                
                IconColumn::make('qr_code')
                    ->label('QR Code')
                    ->boolean()
                    ->trueIcon('heroicon-o-qr-code')
                    ->falseIcon('heroicon-o-x-mark'),
                
                TextColumn::make('used_at')
                    ->label('Used')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Available',
                        'sold' => 'Sold',
                        'used' => 'Used',
                        'refunded' => 'Refunded',
                    ]),
                
                SelectFilter::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'title'),
                
                SelectFilter::make('tier_name')
                    ->label('Ticket Tier')
                    ->options([
                        'VVIP' => 'VVIP',
                        'VIP' => 'VIP',
                        'Regular' => 'Regular',
                        'Terrace' => 'Terrace',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchable();
    }
}