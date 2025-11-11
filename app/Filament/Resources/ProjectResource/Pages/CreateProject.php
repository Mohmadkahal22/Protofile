<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Process images to convert storage paths to API URLs
        if (isset($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as &$image) {
                if (isset($image['image_path']) && $image['image_path']) {
                    $filename = basename($image['image_path']);
                    $image['image_path'] = url('api/storage/projects/images/' . $filename);
                }
            }
        }

        return $data;
    }
}