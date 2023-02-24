<?php declare(strict_types=1);

namespace Tests;

use Masmerise\Toaster\QueuingCollector;

trait CollectorFactoryMethods
{
    private function aCollector(): QueuingCollector
    {
        return new QueuingCollector();
    }
}
