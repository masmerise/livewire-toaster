<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Masmerise\Toaster\Alignment;
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
        yield [['alignment' => Alignment::Bottom->value, 'closeable' => true, 'position' => Position::Right->value]];
        yield [['alignment' => Alignment::Middle->value, 'closeable' => true, 'position' => Position::Right->value]];
        yield [['alignment' => Alignment::Top->value, 'closeable' => true, 'position' => Position::Right->value]];
        yield [['alignment' => Alignment::Bottom->value, 'closeable' => false, 'position' => Position::Left->value]];
        yield [['alignment' => Alignment::Middle->value, 'closeable' => false, 'position' => Position::Left->value]];
        yield [['alignment' => Alignment::Top->value, 'closeable' => false, 'position' => Position::Left->value]];
        yield [['alignment' => Alignment::Bottom->value, 'closeable' => false, 'position' => Position::Center->value]];
        yield [['alignment' => Alignment::Middle->value, 'closeable' => false, 'position' => Position::Center->value]];
        yield [['alignment' => Alignment::Top->value, 'closeable' => false, 'position' => Position::Center->value]];
    }

    #[DataProvider('configurations')]
    #[Test]
    public function it_can_be_rendered(array $config): void
    {
        $component = $this->component(ToasterHub::class, ['config' => ToasterConfig::fromArray($config)]);

        $component->assertSee('toaster');
    }
}
