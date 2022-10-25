<?php

namespace Prantho\Crud;

use Illuminate\Support\ServiceProvider;
use Prantho\Crud\Console\CrudCommand;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            CrudCommand::class,
        ]);

        $this->publishes([
            __DIR__ . '/../stubs' => base_path('resources/crud/stubs'),
        ], 'crud-stubs');
    }
}
