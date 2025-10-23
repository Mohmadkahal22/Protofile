<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditTeam extends EditRecord
{
    protected static string $Resource = TeamResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();

        // Handle photo update
        if (isset($data['photo']) && $data['photo'] && $data['photo'] !== $record->photo) {
            // Delete old photo
            if ($record->photo) {
                $oldPath = parse_url($record->photo, PHP_URL_PATH);
                $oldPath = str_replace('api/storage/', '', $oldPath);
                Storage::disk('public')->delete($oldPath);
            }

            // Convert to API URL
            $filename = basename($data['photo']);
            $data['photo'] = url('api/storage/team_photos/' . $filename);
        } else {
            $data['photo'] = $record->photo;
        }

        // Handle CV update
        if (isset($data['cv_file']) && $data['cv_file'] && $data['cv_file'] !== $record->cv_file) {
            // Delete old CV
            if ($record->cv_file) {
                $oldPath = parse_url($record->cv_file, PHP_URL_PATH);
                $oldPath = str_replace('api/storage/', '', $oldPath);
                Storage::disk('public')->delete($oldPath);
            }

            // Convert to API URL
            $filename = basename($data['cv_file']);
            $data['cv_file'] = url('api/storage/team_cvs/' . $filename);
        } else {
            $data['cv_file'] = $record->cv_file;
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
