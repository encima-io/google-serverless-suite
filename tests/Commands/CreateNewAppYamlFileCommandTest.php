<?php

namespace Encima\Google\Tests\Commands;

use Encima\Google\Tests\TestCase;

class CreateNewAppYamlFileCommandTest extends TestCase
{
    /**
     * @test
     * Created: 2021-01-16
     * Updated: 2021-01-16
     */
    public function it_records_a_successfull_command(): void
    {
        $this->artisan('google-serverless:create')
            ->expectsConfirmation('Do you want to update the log channel in the "app.yaml" file?', 'yes')
            ->expectsOutput('The app.yaml file doesn\'t exists, you should create it and run this command again...')
            ->run();

        $this->assertArrayHasKey('stackdriver', config('logging.channels'));
        // $this->assertContains('stackdriver', file_get_contents(base_path('app.yaml')));
        // $this->assertContains('Bootstrap::exceptionHandler($exception);', file_get_contents(app_path('Exceptions/Handler.php')));
    }
}
