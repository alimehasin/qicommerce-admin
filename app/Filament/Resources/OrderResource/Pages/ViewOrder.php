<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Customer'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('total_amount')
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('shipping_address'),
                        Infolists\Components\TextEntry::make('phone_number'),
                        Infolists\Components\TextEntry::make('note')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Order Items')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')
                                    ->label('Product'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Qty'),
                                Infolists\Components\TextEntry::make('price')
                                    ->money('USD')
                                    ->label('Unit Price'),
                                Infolists\Components\TextEntry::make('total')
                                    ->money('USD')
                                    ->label('Total')
                                    ->state(fn ($record) => $record->quantity * $record->price),
                            ])
                            ->columns(4)
                            ->grid(1),
                    ])
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
