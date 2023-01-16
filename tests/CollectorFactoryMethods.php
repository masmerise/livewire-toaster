<?php declare(strict_types=1);

namespace Tests;

use MAS\Toast\QueuingCollector;

trait CollectorFactoryMethods
{
    private function aCollector(): QueuingCollector
    {
        return new QueuingCollector();
    }
}
