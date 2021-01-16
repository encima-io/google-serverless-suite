<?php

namespace Encima\Google\Commands;

use Illuminate\Console\Command;

class CreateNewAppYamlFileCommand extends Command
{
    public $signature = 'google-serverless:create';

    public $description = 'Install and set up logging for your serverless app.';

    public $runtimes = [
        'PHP 7.3' => 'php73',
        'PHP 7.4' => 'php74',
    ];

    public function handle()
    {
        $path = base_path('php.ini');
        if (!file_exists($path)) {
            if ($this->ask('Do you want to activate the gRPC extension (required by Firestore)? ')) {
                file_put_contents($path, 'extension=grpc.so');
            }
        }

        $path = base_path('app.yaml');
        if (file_exists($path)) {
            $this->comment('You already have a "app.yaml" file in your project! Aborting...');
        }
        $contents = file_get_contents(__DIR__.'/../../stubs/app.yaml');

        $runtime = $this->choice('Which PHP version should be used?', [
            'PHP 7.3',
            'PHP 7.4',
        ]);
        $contents = str_replace('{{RUNTIME}}', $this->runtimes[$runtime], $contents);
        file_put_contents($path, $contents);
        $this->info('The app.yaml has been create: '.$path);
    }
}
