<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Projects;
use App\Models\Services;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends Resource
{
    protected static ?string $model = Projects::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $modelLabel = 'Project';

    protected static ?string $pluralModelLabel = 'Projects';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Project Information')
                    ->schema([
                        Forms\Components\Select::make('service_id')
                            ->label('Service')
                            ->relationship('service', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('Select the service this project belongs to'),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set) {
                                $set('slug', Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('project_url')
                            ->label('Project URL')
                            ->url()
                            ->maxLength(500)
                            ->helperText('Live project URL'),

                        Forms\Components\TextInput::make('video_url')
                            ->label('Video URL')
                            ->url()
                            ->maxLength(500)
                            ->helperText('Project demo video URL (YouTube, Vimeo, etc.)'),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000)
                            ->helperText('Project description (max 1000 characters)'),
                    ])->columns(1),
                
                Forms\Components\Section::make('Project Images')
                    ->schema([
                        Forms\Components\Repeater::make('images')
                            ->relationship('images')
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->label('Project Image')
                                    ->image()
                                    ->directory('projects/images')
                                    ->maxSize(2048)
                                    ->required()
                                    ->helperText('Upload project image (max 2MB)'),

                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['alt_text'] ?? 'New Image')
                            ->helperText('Add multiple images for this project. You can reorder them by dragging.'),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($get) => !count($get('images') ?? [])),
                
                Forms\Components\Section::make('Project Features')
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->relationship('features')
                            ->schema([
                                Forms\Components\Textarea::make('feature_text')
                                    ->label('Feature')
                                    ->required()
                                    ->rows(2)
                                    ->maxLength(500)
                                    ->helperText('Describe a key feature of this project'),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                $state['feature_text'] ? Str::limit($state['feature_text'], 50) : 'New Feature'
                            )
                            ->helperText('Add key features of this project. You can reorder them by dragging.'),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($get) => !count($get('features') ?? [])),
            ]);
    }
    // ... keep the rest of your table, relations, and pages methods the same
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.title')
                    ->label('Service')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(100)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('project_url')
                    ->label('Project URL')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('video_url')
                    ->label('Video URL')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('images_count')
                    ->label('Images')
                    ->counts('images')
                    ->sortable()
                    ->color('success'),

                Tables\Columns\TextColumn::make('features_count')
                    ->label('Features')
                    ->counts('features')
                    ->sortable()
                    ->color('warning'),

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
                Tables\Filters\SelectFilter::make('service')
                    ->relationship('service', 'title')
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}