<?php

namespace Encima\Google;

use Encima\Google\Commands\InstallLoggingCommand;
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
            InstallLoggingCommand::class,
        ]);

        return $this;
    }
}
