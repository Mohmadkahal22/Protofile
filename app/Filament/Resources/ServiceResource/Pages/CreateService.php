<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Convert Filament's storage paths to API URLs
        if (isset($data['image_path']) && $data['image_path']) {
            $filename = basename($data['image_path']);
            $data['image_path'] = url('api/storage/services/' . $filename);
        }

        return $data;
    }
}