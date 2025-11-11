<?php

namespace App\Filament\Resources\AboutUsResource\Pages;

use App\Filament\Resources\AboutUsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditAboutUs extends EditRecord
{
    protected static string $resource = AboutUsResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();

        // Handle logo update
        if (isset($data['company_logo']) && $data['company_logo'] && $data['company_logo'] !== $record->company_logo) {
            // Delete old logo
            if ($record->company_logo) {
                $oldPath = parse_url($record->company_logo, PHP_URL_PATH);
                $oldPath = str_replace('api/storage/', '', $oldPath);
                Storage::disk('public')->delete($oldPath);
            }

            // Convert to API URL
            $filename = basename($data['company_logo']);
            $data['company_logo'] = url('api/storage/company/logo/' . $filename);
        } else {
            $data['company_logo'] = $record->company_logo;
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