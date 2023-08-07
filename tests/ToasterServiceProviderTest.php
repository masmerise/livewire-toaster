<?php declare(strict_types=1);

namespace Tests;

use Dive\Crowbar\Crowbar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Masmerise\Toaster\AccessibleCollector;
use Masmerise\Toaster\Collector;
use Masmerise\Toaster\LivewireRelay;
use Masmerise\Toaster\SessionRelay;
use Masmerise\Toaster\ToasterConfig;
use Masmerise\Toaster\ToasterHub;
use Masmerise\Toaster\ToasterServiceProvider;
use Masmerise\Toaster\TranslatingCollector;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ToasterServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(ToasterServiceProvider::class);
    }

    #[Test]
    public function it_binds_the_service_as_a_singleton(): void
    {
        $this->assertTrue($this->app->isShared(Collector::class));
        $this->assertTrue($this->app->isAlias(ToasterServiceProvider::NAME));
    }

    #[Test]
    public function it_registers_the_livewire_relay_only_after_the_service_has_been_resolved_at_least_once(): void
    {
        $livewire = Crowbar::pry($this->app['livewire']);

        $this->assertNotContains(LivewireRelay::class, $livewire->listeners['component.dehydrate']);

        $this->app[ToasterServiceProvider::NAME];

        $this->assertInstanceOf(LivewireRelay::class, Arr::last($livewire->listeners['component.dehydrate']));
    }

    #[Test]
    public function it_registers_the_session_relay_as_middleware(): void
    {
        $router = Crowbar::pry($this->app['router']);

        $middleware = array_reverse($router->middlewareGroups['web']);
        $middleware = $middleware[0];

        $this->assertSame(SessionRelay::NAME, $middleware);
    }

    #[Test]
    public function it_registers_the_toast_hub_as_a_blade_component(): void
    {
        $blade = Crowbar::pry($this->app['blade.compiler']);

        $this->assertArrayHasKey('toaster-hub', $blade->classComponentAliases);
        $this->assertSame(ToasterHub::class, $blade->classComponentAliases['toaster-hub']);
    }

    #[Test]
    public function it_registers_the_translating_behaviour_only_if_enabled_in_the_config(): void
    {
        $this->assertInstanceOf(TranslatingCollector::class, $this->app[ToasterServiceProvider::NAME]);

        $this->refreshApplication();
        $this->app['config']->set('toaster.translate', false);
        $this->app->register(ToasterServiceProvider::class);

        $this->assertInstanceOf(AccessibleCollector::class, $this->app[ToasterServiceProvider::NAME]);
    }

    #[Test]
    public function it_registers_macros(): void
    {
        $this->assertTrue(RedirectResponse::hasMacro('error'));
        $this->assertTrue(RedirectResponse::hasMacro('info'));
        $this->assertTrue(RedirectResponse::hasMacro('success'));
        $this->assertTrue(RedirectResponse::hasMacro('warning'));
    }

    #[Test]
    public function it_registers_custom_config_object(): void
    {
        $this->assertTrue($this->app->bound(ToasterConfig::class));

        $config = $this->app[ToasterConfig::class];

        $this->assertSame(3000, $config->duration);
        $this->assertTrue($config->wantsAccessibility);
        $this->assertTrue($config->wantsCloseableToasts);
        $this->assertTrue($config->wantsTranslation);
        $this->assertSame('right', $config->position);
    }
}
