<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutUsResource\Pages;
use App\Models\About_Us;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class AboutUsResource extends Resource
{
    protected static ?string $model = About_Us::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Company Settings';

    protected static ?string $modelLabel = 'About Us';

    protected static ?string $pluralModelLabel = 'About Us';

    protected static ?string $navigationLabel = 'About Us';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Company Information')
                    ->schema([
                        Forms\Components\FileUpload::make('company_logo')
                            ->label('Company Logo')
                            ->image()
                            ->directory('company/logo')
                            ->maxSize(1024)
                            ->imageResizeMode('contain')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('200')
                            ->imageResizeTargetHeight('200')
                            ->helperText('Upload company logo (max 1MB, recommended: 200x200px)'),

                        Forms\Components\TextInput::make('company_name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('The official name of your company'),

                        Forms\Components\DatePicker::make('foundation_date')
                            ->label('Foundation Date')
                            ->required()
                            ->maxDate(now())
                            ->helperText('When was the company founded?'),

                        Forms\Components\TextInput::make('contact_email')
                            ->label('Contact Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->helperText('Primary contact email address'),

                        Forms\Components\TextInput::make('website_url')
                            ->label('Website URL')
                            ->url()
                            ->maxLength(255)
                            ->helperText('Company website URL'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Company Description')
                    ->schema([
                        Forms\Components\RichEditor::make('company_description')
                            ->required()
                            ->maxLength(2000)
                            ->fileAttachmentsDirectory('company/attachments')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'blockquote',
                                'bulletList',
                                'orderedList',
                                'link',
                            ])
                            ->helperText('Detailed description about your company, mission, and values'),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Social Media Links')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://facebook.com/')
                            ->helperText('Facebook profile/page URL'),

                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://instagram.com/')
                            ->helperText('Instagram profile URL'),

                        Forms\Components\TextInput::make('linkedin_url')
                            ->label('LinkedIn URL')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://linkedin.com/')
                            ->helperText('LinkedIn company page URL'),

                        Forms\Components\TextInput::make('github_url')
                            ->label('GitHub URL')
                            ->url()
                            ->maxLength(255)
                            ->prefix('https://github.com/')
                            ->helperText('GitHub organization/profile URL'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('company_logo')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-company.png')),

                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),

                Tables\Columns\TextColumn::make('contact_email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->limit(25),

                Tables\Columns\TextColumn::make('website_url')
                    ->label('Website')
                    ->limit(20)
                    ->icon('heroicon-o-globe-alt'),

                Tables\Columns\TextColumn::make('foundation_date')
                    ->label('Founded')
                    ->date()
                    ->sortable(),

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
                //
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
            'index' => Pages\ListAboutUs::route('/'),
            'create' => Pages\CreateAboutUs::route('/create'),
            'edit' => Pages\EditAboutUs::route('/{record}/edit'),
        ];
    }
}