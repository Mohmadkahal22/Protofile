<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();

        // Process images to convert storage paths to API URLs and handle deletions
        if (isset($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as &$image) {
                if (isset($image['image_path']) && $image['image_path']) {
                    // Check if this is a new upload (not already a URL)
                    if (!filter_var($image['image_path'], FILTER_VALIDATE_URL)) {
                        $filename = basename($image['image_path']);
                        $image['image_path'] = url('api/storage/projects/images/' . $filename);
                    }
                }
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}