<?php declare(strict_types=1);

namespace Tests;

use MAS\Toast\Collector;
use MAS\Toast\TranslatingCollector;
use MAS\Toast\ToastServiceProvider;

final class ProviderTest extends TestCase
{
    /** @test */
    public function it_binds_the_service_as_a_singleton(): void
    {
        $this->assertTrue($this->app->isShared(Collector::class));
        $this->assertTrue($this->app->isAlias(ToastServiceProvider::NAME));
        $this->assertInstanceOf(TranslatingCollector::class, $this->app[ToastServiceProvider::NAME]);
    }
}
