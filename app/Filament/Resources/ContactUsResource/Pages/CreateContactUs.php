<?php

namespace App\Filament\Resources\ContactUsResource\Pages;

use App\Filament\Resources\ContactUsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateContactUs extends CreateRecord
{
    protected static string $resource = ContactUsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        return $data;
    }

    protected function beforeCreate(): void
    {
        DB::beginTransaction();
    }

    protected function afterCreate(): void
    {
        DB::commit();
        
        \Filament\Notifications\Notification::make()
            ->title('Contact message created successfully')
            ->success()
            ->send();
    }

    protected function onValidationError(\Illuminate\Validation\ValidationException $exception): void
    {
        DB::rollBack();
        throw $exception;
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->action(function () {
                try {
                    $this->create();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Contact message creation failed: ' . $e->getMessage());
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Creation failed')
                        ->body('Failed to create contact message')
                        ->danger()
                        ->send();
                        
                    throw $e;
                }
            });
    }
}