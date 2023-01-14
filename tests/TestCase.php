<?php declare(strict_types=1);

namespace Tests;

use MAS\Toast\ToastServiceProvider;
use Orchestra\Testbench\TestCase as TestCaseBase;

abstract class TestCase extends TestCaseBase
{
    protected function getPackageProviders($app): array
    {
        return [ToastServiceProvider::class];
    }
}
