<?php declare(strict_types=1);

namespace Tests;

use MAS\Toaster\QueuingCollector;

trait CollectorFactoryMethods
{
    private function aCollector(): QueuingCollector
    {
        return new QueuingCollector();
    }
}
