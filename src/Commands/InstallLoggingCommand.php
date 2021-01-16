<?php

namespace Encima\Google\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use ReflectionClass;

class InstallLoggingCommand extends Command
{
    public $signature = 'google-serverless:install:logging';

    public $description = 'Install and set up logging for your serverless app.';

    public function handle()
    {
        $this->comment('Installing the Google Cloud Platform log driver...');

        $this->info('Checking if the stackdriver is already installed...');
        $this->info('');

        if (!$this->isLoggingConfigUpdated()) {
            if ($this->configureLoggingConfig() === false) {
                $this->comment('Failed to install config...');
                $this->comment('You should update the logging config file manually...');
            }
        }

        if (!$this->isLoggingExceptions()) {
            if ($this->ask('Do you want to log exceptions?', 'Yes')) {
                $this->configureExcetionLogging();
            }
        }

        if ($this->ask('Do you want to update the log channel in the "app.yaml" file?')) {
            $this->updateAppYamlFile();
        }
        $this->info('The driver is installed!');
    }

    public function isLoggingConfigUpdated(): bool
    {
        return config('logging.channels.stackdriver') !== null;
    }

    public function configureLoggingConfig(): bool
    {
        $bytes = file_put_contents(
            $path = config_path('logging.php'),
            Str::replaceFirst(
                "'channels' => [",
                "'channels' => [
        'stackdriver' => [
            'driver' => 'custom',
            'via' => Encima\Google\Logging\CreateStackdriverLogger::class,
            'level' => 'debug',
        ],",
                file_get_contents($path),
            )
        );

        return $bytes !== false;
    }

    public function isLoggingExceptions()
    {
        $reflection = new ReflectionClass(\App\Exceptions\Handler::class);
        $method = $reflection->getMethod('report');

        return $method->class !== 'Illuminate\Foundation\Exceptions\Handler';
    }

    public function configureExcetionLogging()
    {
        $reflection = new ReflectionClass(\App\Exceptions\Handler::class);
        $path = $reflection->getFileName();

        $contents = file_get_contents($path);
        $lookup = '/**
     * Register the exception handling callbacks for the application.';
        file_put_contents(
            $path,
            Str::replaceFirst(
                 $lookup,
                file_get_contents(__DIR__.'/../../stubs/exception-logging.stub').$lookup,
                $contents
            )
        );
    }

    public function updateAppYamlFile()
    {
        $path = base_path('app.yaml');
        if (!file_exists($path)) {
            $this->comment('The app.yaml file doesn\'t exists, you should create it and run this command again...');

            return;
        }
        $contents = file_get_contents($path);
        if (strpos($contents, 'stackdriver') === false) {
            file_put_contents(
                $path,
                str_replace(
                    'env_variables:',
                    'env_variables:
  LOG_CHANNEL: stackdriver',
                $contents
                )
            );
        }
    }
}
