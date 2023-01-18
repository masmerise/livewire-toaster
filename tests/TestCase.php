<?php declare(strict_types=1);

namespace Tests;

use MAS\Toaster\ToasterServiceProvider;
use Orchestra\Testbench\TestCase as TestCaseBase;

abstract class TestCase extends TestCaseBase
{
    protected function getPackageProviders($app): array
    {
        return [ToasterServiceProvider::class];
    }
}
