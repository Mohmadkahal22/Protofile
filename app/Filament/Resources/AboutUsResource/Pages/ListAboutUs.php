<?php

namespace App\Filament\Resources\AboutUsResource\Pages;

use App\Filament\Resources\AboutUsResource;
use App\Models\About_Us;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAboutUs extends ListRecords
{
    protected static string $resource = AboutUsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->hidden(fn () => About_Us::count() >= 1)
                ->disabled(fn () => About_Us::count() >= 1)
                ->tooltip(fn () => About_Us::count() >= 1 ? 'Only one About Us record can exist' : ''),
        ];
    }

    protected function getTableEmptyStateActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->hidden(fn () => About_Us::count() >= 1)
                ->disabled(fn () => About_Us::count() >= 1)
                ->tooltip(fn () => About_Us::count() >= 1 ? 'Only one About Us record can exist' : ''),
        ];
    }
}