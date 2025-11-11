<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReview extends CreateRecord
{
    protected static string $resource = ReviewResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default approval status if not provided
        if (!isset($data['is_approved'])) {
            $data['is_approved'] = true;
        }

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Review created successfully';
    }
}