<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactUsResource\Pages;
use App\Models\Contact_Us;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactUsResource extends Resource
{
    protected static ?string $model = Contact_Us::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $modelLabel = 'Contact Message';

    protected static ?string $pluralModelLabel = 'Contact Messages';

    protected static ?string $navigationLabel = 'Contact Messages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Full name of the contact person'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->helperText('Email address for follow-up'),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->helperText('Phone number with country code'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Message Details')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Subject of the message'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false)
                            ->helperText('Current status of the contact message'),

                        Forms\Components\Textarea::make('message')
                            ->required()
                            ->rows(6)
                            ->maxLength(1000)
                            ->helperText('The main message content'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->limit(25),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-phone')
                    ->limit(15),

                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('message')
                    ->searchable()
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                    ])
                    ->rules(['required', 'in:pending,in_progress,completed'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->description(fn ($record) => $record->created_at->format('M j, Y g:i A')),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('markInProgress')
                        ->label('Mark In Progress')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(function (Contact_Us $record) {
                            try {
                                DB::beginTransaction();
                                $record->update(['status' => 'in_progress']);
                                DB::commit();
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Status Updated')
                                    ->body('Contact message marked as in progress')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                DB::rollBack();
                                Log::error('Failed to update contact status: ' . $e->getMessage());
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Update Failed')
                                    ->body('Failed to update contact status')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->hidden(fn (Contact_Us $record) => $record->status === 'in_progress'),

                    Tables\Actions\Action::make('markCompleted')
                        ->label('Mark Completed')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->action(function (Contact_Us $record) {
                            try {
                                DB::beginTransaction();
                                $record->update(['status' => 'completed']);
                                DB::commit();
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Status Updated')
                                    ->body('Contact message marked as completed')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                DB::rollBack();
                                Log::error('Failed to update contact status: ' . $e->getMessage());
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Update Failed')
                                    ->body('Failed to update contact status')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->hidden(fn (Contact_Us $record) => $record->status === 'completed'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            DB::beginTransaction();
                            try {
                                foreach ($records as $record) {
                                    $record->delete();
                                }
                                DB::commit();
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Messages Deleted')
                                    ->body('Selected contact messages deleted successfully')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                DB::rollBack();
                                Log::error('Bulk contact message deletion failed: ' . $e->getMessage());
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Deletion Failed')
                                    ->body('Failed to delete selected contact messages')
                                    ->danger()
                                    ->send();
                            }
                        }),
                    
                    Tables\Actions\BulkAction::make('markAsCompleted')
                        ->label('Mark as Completed')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->action(function ($records) {
                            DB::beginTransaction();
                            try {
                                foreach ($records as $record) {
                                    $record->update(['status' => 'completed']);
                                }
                                DB::commit();
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Status Updated')
                                    ->body('Selected messages marked as completed')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                DB::rollBack();
                                Log::error('Bulk status update failed: ' . $e->getMessage());
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Update Failed')
                                    ->body('Failed to update selected messages')
                                    ->danger()
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactUs::route('/'),
            'create' => Pages\CreateContactUs::route('/create'),
            'edit' => Pages\EditContactUs::route('/{record}/edit'),
        ];
    }
}