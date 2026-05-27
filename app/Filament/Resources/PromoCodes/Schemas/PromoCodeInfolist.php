<?php

namespace App\Filament\Resources\PromoCodes\Schemas;

use App\Models\PromoCode;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PromoCodeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('discount_type')
                    ->badge(),
                TextEntry::make('discount')
                    ->numeric(),
                TextEntry::make('valid_until')
                    ->dateTime(),
                IconEntry::make('is_used')
                    ->boolean(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (PromoCode $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
