<?php

namespace App\Filament\Resources\AboutUsResource\Pages;

use App\Filament\Resources\AboutUsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateAboutUs extends CreateRecord
{
    protected static string $resource = AboutUsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Convert Filament's storage paths to API URLs
        if (isset($data['company_logo']) && $data['company_logo']) {
            $filename = basename($data['company_logo']);
            $data['company_logo'] = url('api/storage/company/logo/' . $filename);
        }

        return $data;
    }
}