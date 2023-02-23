<?php declare(strict_types=1);

namespace Tests;

use Dive\Crowbar\Crowbar;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use MAS\Toaster\AccessibleCollector;
use MAS\Toaster\Collector;
use MAS\Toaster\LivewireRelay;
use MAS\Toaster\SessionRelay;
use MAS\Toaster\ToasterConfig;
use MAS\Toaster\ToasterHub;
use MAS\Toaster\TranslatingCollector;
use MAS\Toaster\ToasterServiceProvider;
use Orchestra\Testbench\TestCase;

final class ToasterServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(ToasterServiceProvider::class);
    }

    /** @test */
    public function it_binds_the_service_as_a_singleton(): void
    {
        $this->assertTrue($this->app->isShared(Collector::class));
        $this->assertTrue($this->app->isAlias(ToasterServiceProvider::NAME));
    }

    /** @test */
    public function it_registers_the_relays_only_after_the_service_has_been_resolved_at_least_once(): void
    {
        $events = Crowbar::pry($this->app['events']);
        $livewire = Crowbar::pry($this->app['livewire']);

        $this->assertNotContains(SessionRelay::class, $events->listeners[RequestHandled::class] ?? []);
        $this->assertNotContains(LivewireRelay::class, $livewire->listeners['component.dehydrate']);

        $this->app[ToasterServiceProvider::NAME];

        $this->assertContains(SessionRelay::class, $events->listeners[RequestHandled::class]);
        $this->assertInstanceOf(LivewireRelay::class, Arr::last($livewire->listeners['component.dehydrate']));
    }

    /** @test */
    public function it_registers_the_toast_hub_as_a_blade_component(): void
    {
        $blade = Crowbar::pry($this->app['blade.compiler']);

        $this->assertArrayHasKey('toaster-hub', $blade->classComponentAliases);
        $this->assertSame(ToasterHub::class, $blade->classComponentAliases['toaster-hub']);
    }

    /** @test */
    public function it_registers_the_translating_behaviour_only_if_enabled_in_the_config(): void
    {
        $this->assertInstanceOf(TranslatingCollector::class, $this->app[ToasterServiceProvider::NAME]);

        $this->refreshApplication();
        $this->app['config']->set('toaster.translate', false);
        $this->app->register(ToasterServiceProvider::class);

        $this->assertInstanceOf(AccessibleCollector::class, $this->app[ToasterServiceProvider::NAME]);
    }

    /** @test */
    public function it_registers_macros(): void
    {
        $this->assertTrue(RedirectResponse::hasMacro('error'));
        $this->assertTrue(RedirectResponse::hasMacro('info'));
        $this->assertTrue(RedirectResponse::hasMacro('success'));
        $this->assertTrue(RedirectResponse::hasMacro('warning'));
    }

    /** @test */
    public function it_registers_custom_config_object(): void
    {
        $this->assertTrue($this->app->bound(ToasterConfig::class));

        $config = $this->app[ToasterConfig::class];

        $this->assertSame(5000, $config->duration);
        $this->assertTrue($config->wantsAccessibility);
        $this->assertTrue($config->wantsTranslation);
        $this->assertSame('right', $config->position);
    }
}
