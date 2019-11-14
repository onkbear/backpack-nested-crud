<?php

namespace Onkbear\NestedCrud;

use Illuminate\Support\ServiceProvider;

class NestedCrudServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        // load overwritten views
        $customNestedCrudFolder = resource_path('views/vendor/backpack/nested_crud');
        if (file_exists($customNestedCrudFolder)) {
            $this->loadViewsFrom($customNestedCrudFolder, 'nested_crud');
        }

        // load default views
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views/crud'), 'crud');
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views/nested_crud'), 'nested_crud');
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
    }
}
