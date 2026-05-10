<?php

namespace App\Filament\Resources\Venues\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VenuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('name')
                    ->label('Venue Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('location')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('city')
                    ->label('City')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Nairobi' => 'success',
                        'Mombasa' => 'info',
                        'Kisumu' => 'warning',
                        default => 'gray',
                    }),
                
                TextColumn::make('capacity')
                    ->label('Capacity')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? number_format($state) . ' seats' : 'N/A'),
                
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                
                TextColumn::make('events_count')
                    ->label('Total Events')
                    ->counts('events')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('upcoming_events_count')
                    ->label('Upcoming')
                    ->counts('upcomingEvents')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('city')
                    ->label('City')
                    ->options([
                        'Nairobi' => 'Nairobi',
                        'Mombasa' => 'Mombasa',
                        'Kisumu' => 'Kisumu',
                        'Nakuru' => 'Nakuru',
                        'Eldoret' => 'Eldoret',
                    ]),
                
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
                
                SelectFilter::make('capacity_range')
                    ->label('Capacity Range')
                    ->options([
                        'small' => 'Small (< 10,000)',
                        'medium' => 'Medium (10,000 - 30,000)',
                        'large' => 'Large (> 30,000)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value']) {
                            'small' => $query->where('capacity', '<', 10000),
                            'medium' => $query->whereBetween('capacity', [10000, 30000]),
                            'large' => $query->where('capacity', '>', 30000),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->label('View'),
                EditAction::make()
                    ->label('Edit'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete Selected'),
                ]),
            ])
            ->defaultSort('name', 'asc')
            ->searchable()
            ->persistFiltersInSession()
            ->striped();
    }
}