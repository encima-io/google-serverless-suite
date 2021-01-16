<?php

namespace Encima\Google;

use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
    }

    public function register()
    {
        //
    }

    protected function registerCommands(): self
    {
        $this->commands([
            Commands\CreateNewAppYamlFileCommand::class,
            Commands\InstallLoggingCommand::class,
        ]);

        return $this;
    }
}
