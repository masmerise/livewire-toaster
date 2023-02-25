<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Masmerise\Toaster\Position;
use Masmerise\Toaster\ToasterConfig;
use Masmerise\Toaster\ToasterHub;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class ToasterHubTest extends TestCase
{
    use InteractsWithViews;

    public static function configurations(): iterable
    {
        yield [ToasterConfig::fromArray(['closeable' => true, 'position' => Position::Right->value])];
        yield [ToasterConfig::fromArray(['closeable' => false, 'position' => Position::Left->value])];
        yield [ToasterConfig::fromArray(['closeable' => false, 'position' => Position::Center->value])];
    }

    #[DataProvider('configurations')]
    #[Test]
    public function it_can_be_rendered(ToasterConfig $config): void
    {
        $component = $this->component(ToasterHub::class, compact('config'));

        $component->assertSee('toaster');
    }
}
