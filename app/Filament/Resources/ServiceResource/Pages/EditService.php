<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();

        // Handle image update
        if (isset($data['image_path']) && $data['image_path'] && $data['image_path'] !== $record->image_path) {
            // Delete old image
            if ($record->image_path) {
                $oldPath = parse_url($record->image_path, PHP_URL_PATH);
                $oldPath = str_replace('api/storage/', '', $oldPath);
                Storage::disk('public')->delete($oldPath);
            }

            // Convert to API URL
            $filename = basename($data['image_path']);
            $data['image_path'] = url('api/storage/services/' . $filename);
        } else {
            $data['image_path'] = $record->image_path;
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