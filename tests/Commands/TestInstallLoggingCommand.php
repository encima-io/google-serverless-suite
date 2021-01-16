<?php

namespace Encima\Google\Tests\Commands;

use Encima\Google\Tests\TestCase;

class TestInstallLoggingCommand extends TestCase
{
    /**
     * @test
     * Created: 2021-01-16
     * Updated: 2021-01-16
     */
    public function it_records_a_successfull_command(): void
    {
        $this->artisan('google-serverless:install:logging')->run();

        $this->assertArrayHasKey('stackdriver', config('logging.channels'));
        $this->assertEquals('stackdriver', config('logging.default'));
        $this->assertContains('Bootstrap::exceptionHandler($exception);', file_get_contents(app_path('Exceptions/Handler.php')));
    }
}
