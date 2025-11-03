<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
             \Filament\Tables\Columns\TextColumn::configureUsing(function ($column) {
            if (method_exists($column, 'dateTime')) {
                $column->date('Y-m-d H:i:s');
            }
        });
    }
}
