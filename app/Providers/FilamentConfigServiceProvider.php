<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Tables\Columns\TextColumn;

class FilamentConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Override the dateTime method to use simple date format
        TextColumn::macro('safeDateTime', function () {
            /** @var TextColumn $this */
            return $this->date('Y-m-d H:i:s');
        });
    }

    public function register(): void
    {
        //
    }
}
