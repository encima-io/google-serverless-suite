<?php

namespace Encima\Google\Tests;

use Encima\Google\Commands\InstallLoggingCommand;
use Encima\Google\GoogleServiceProvider;
use Illuminate\Console\Application as Artisan;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Setup the test case.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::starting(function ($artisan) {
            $artisan->resolveCommands(InstallLoggingCommand::class);
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            GoogleServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        // $app['config']->set('database.default', 'testing');
        // $app['config']->set('services.command-logger.loggable_commands', [
        //     'commandlogger:success:test',
        //     'commandlogger:fail:test',
        // ]);
    }

    /**
     * Load the migrations for the test environment.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        // $this->loadMigrationsFrom([
        //     '--database' => 'testing',
        //     '--path' => realpath(__DIR__.'/../database/migrations'),
        //     '--realpath' => true,
        // ]);
    }
}
