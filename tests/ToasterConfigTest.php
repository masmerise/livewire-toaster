<?php declare(strict_types=1);

namespace Tests;

use Masmerise\Toaster\ToasterConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ToasterConfigTest extends TestCase
{
    #[Test]
    public function it_can_be_serialized_for_the_frontend(): void
    {
        $config = ToasterConfig::fromArray(require __DIR__ . '/../config/toaster.php');

        $array = $config->toJavaScript();

        $this->assertSame(['duration' => 3000], $array);
    }
}
