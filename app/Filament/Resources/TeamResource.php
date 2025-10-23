<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Team Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->label('Profile Photo')
                            ->image()
                            ->directory('team_photos')
                            ->maxSize(2048)
                            ->helperText('Upload profile photo (max 2MB)'),

                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])->columns(2),

                Forms\Components\Section::make('Professional Information')
                    ->schema([
                        Forms\Components\TextInput::make('position')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('specialization')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('github_url')
                            ->url()
                            ->maxLength(255)
                            ->helperText('Full GitHub profile URL'),

                        Forms\Components\FileUpload::make('cv_file')
                            ->label('CV/Resume')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->directory('team_cvs')
                            ->maxSize(5120)
                            ->helperText('Upload PDF or Word document (max 5MB)'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),

                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('specialization')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('github_url')
                    ->label('GitHub')
                    ->icon('heroicon-m-link')
                    ->url(fn ($record) => $record->github_url)
                    ->openUrlInNewTab(),

                Tables\Columns\IconColumn::make('cv_file')
                    ->label('CV')
                    ->icon('heroicon-m-document')
                    ->url(fn ($record) => $record->cv_file)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
