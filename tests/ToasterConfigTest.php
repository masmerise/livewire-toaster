<?php declare(strict_types=1);

namespace Tests;

use MAS\Toaster\ToasterConfig;
use PHPUnit\Framework\TestCase;

final class ToasterConfigTest extends TestCase
{
    /** @test */
    public function it_is_defaultable_to_be_used_on_the_frontend(): void
    {
        $config = ToasterConfig::fromArray(require __DIR__ . '/../config/toaster.php');

        $array = $config->toDefaults();

        $this->assertSame(['duration' => 5000], $array);
    }
}
