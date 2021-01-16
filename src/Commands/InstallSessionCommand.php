<?php

namespace Encima\Google\Commands;

use Illuminate\Console\Command;

class InstallSessionCommand extends Command
{
    public $signature = 'google-serverless:install:session';

    public $description = 'Install and set up sessions for your serverless app.';

    public function handle()
    {
        $this->comment('Configuring your sessions driver...');

        $path = config_path('session.php');
        $contents = file_get_contents($path);

        if (strpos($contents, "'driver' => env('SESSION_DRIVER', 'file')")) {
            if ($this->ask('Do you want to change the fallback sessions to cookies?')) {
                file_put_contents(
                    $path,
                    str_replace(
                        "'driver' => env('SESSION_DRIVER', 'file')",
                        "'driver' => env('SESSION_DRIVER', 'cookie')",
                        $contents
                    )
                );
            }
        }
        $this->info('A serverless app should use a centralized store that all instances can access, such as Redis or a database');
        $sessionDriver = $this->choice('Which sessions version should be used?', [
            'database',
            'redis',
            'firestore',
            'cookie',
        ]);

        if ($sessionDriver === 'firestore') {
            if (!$this->isLoggingConfigUpdated()) {
                $this->installFirestoreSessionDriver();
            }
        }

        $this->updateAppYamlFile($sessionDriver);

        $this->info('The driver is installed!');
    }

    public function installFirestoreSessionDriver()
    {
    }

    public function updateAppYamlFile($sessionDriver)
    {
        $path = base_path('app.yaml');
        if (!file_exists($path)) {
            $this->comment('The app.yaml file doesn\'t exists, you should create it and run this command again...');

            return;
        }
        $contents = file_get_contents($path);
        if (strpos($contents, $sessionDriver) === false) {
            file_put_contents(
                $path,
                str_replace(
                    'env_variables:',
                    'env_variables:
  SESSION_DRIVER: '.$sessionDriver,
                $contents
                )
            );
        }
    }
}
