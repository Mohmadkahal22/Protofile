<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Convert Filament's storage paths to API URLs
        if (isset($data['photo']) && $data['photo']) {
            $filename = basename($data['photo']);
            $data['photo'] = url('api/storage/team_photos/' . $filename);
        }

        if (isset($data['cv_file']) && $data['cv_file']) {
            $filename = basename($data['cv_file']);
            $data['cv_file'] = url('api/storage/team_cvs/' . $filename);
        }

        return $data;
    }
}
