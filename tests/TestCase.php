<?php

namespace Oinpentuls\BcaApi\Tests;

use Oinpentuls\BcaApi\BCAServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            BCAServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetup($app)
    {

    }
}
