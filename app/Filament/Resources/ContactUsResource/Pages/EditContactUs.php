<?php

namespace App\Filament\Resources\ContactUsResource\Pages;

use App\Filament\Resources\ContactUsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditContactUs extends EditRecord
{
    protected static string $resource = ContactUsResource::class;

    protected function beforeSave(): void
    {
        DB::beginTransaction();
    }

    protected function afterSave(): void
    {
        DB::commit();
        
        \Filament\Notifications\Notification::make()
            ->title('Contact message updated successfully')
            ->success()
            ->send();
    }

    protected function beforeDelete(): void
    {
        DB::beginTransaction();
    }

    protected function afterDelete(): void
    {
        DB::commit();
        
        \Filament\Notifications\Notification::make()
            ->title('Contact message deleted successfully')
            ->success()
            ->send();
    }

    protected function onValidationError(\Illuminate\Validation\ValidationException $exception): void
    {
        DB::rollBack();
        throw $exception;
    }

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->action(function () {
                try {
                    $this->save();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Contact message update failed: ' . $e->getMessage());
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Update failed')
                        ->body('Failed to update contact message')
                        ->danger()
                        ->send();
                        
                    throw $e;
                }
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->action(function () {
                    try {
                        $this->delete();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Contact message deletion failed: ' . $e->getMessage());
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Deletion failed')
                            ->body('Failed to delete contact message')
                            ->danger()
                            ->send();
                            
                        throw $e;
                    }
                }),
        ];
    }
}