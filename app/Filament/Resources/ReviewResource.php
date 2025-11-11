<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $modelLabel = 'Review';

    protected static ?string $pluralModelLabel = 'Reviews';

    protected static ?string $navigationLabel = 'Reviews';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Name of the reviewer'),

                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000)
                            ->helperText('The review content'),

                        Forms\Components\Select::make('rating')
                            ->options([
                                1 => '1 Star - Poor',
                                2 => '2 Stars - Fair',
                                3 => '3 Stars - Good',
                                4 => '4 Stars - Very Good',
                                5 => '5 Stars - Excellent',
                            ])
                            ->required()
                            ->native(false)
                            ->helperText('Rating from 1 to 5 stars'),

                        Forms\Components\DatePicker::make('review_date')
                            ->label('Review Date')
                            ->required()
                            ->default(now())
                            ->maxDate(now())
                            ->helperText('Date when the review was written'),

                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approved')
                            ->default(true)
                            ->helperText('Approve this review to show it publicly'),
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

                Tables\Columns\TextColumn::make('content')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state . ' ' . str_repeat('⭐', $state))
                    ->color(function ($state) {
                        return match (true) {
                            $state >= 4 => 'success',
                            $state >= 3 => 'warning',
                            default => 'danger',
                        };
                    })
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean()
                    ->sortable()
                    ->action(function ($record, Tables\Columns\IconColumn $column) {
                        $record->update([
                            'is_approved' => !$record->is_approved
                        ]);
                    }),

                Tables\Columns\TextColumn::make('review_date')
                    ->label('Review Date')
                    ->date()
                    ->sortable()
                    ->description(fn ($record) => $record->review_date ? Carbon::parse($record->review_date)->diffForHumans() : ''),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars', 
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ])
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Approval Status')
                    ->trueLabel('Approved')
                    ->falseLabel('Not Approved')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->action(function (Review $record) {
                            $record->update(['is_approved' => true]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Review Approved')
                                ->body('The review has been approved and will be shown publicly')
                                ->success()
                                ->send();
                        })
                        ->hidden(fn (Review $record) => $record->is_approved),

                    Tables\Actions\Action::make('disapprove')
                        ->label('Disapprove')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(function (Review $record) {
                            $record->update(['is_approved' => false]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Review Disapproved')
                                ->body('The review has been disapproved and will not be shown publicly')
                                ->warning()
                                ->send();
                        })
                        ->hidden(fn (Review $record) => !$record->is_approved),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approveSelected')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_approved' => true]);
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Reviews Approved')
                                ->body('Selected reviews have been approved')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('disapproveSelected')
                        ->label('Disapprove Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_approved' => false]);
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Reviews Disapproved')
                                ->body('Selected reviews have been disapproved')
                                ->warning()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('review_date', 'desc');
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}