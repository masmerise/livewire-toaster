<?php declare(strict_types=1);

namespace Tests;

use MAS\Toast\ToastConfig;
use PHPUnit\Framework\TestCase;

final class ToastConfigTest extends TestCase
{
    /** @test */
    public function it_is_arrayable_to_be_used_on_the_frontend(): void
    {
        $config = ToastConfig::fromArray(require __DIR__ . '/../config/toast.php');

        $array = $config->toArray();

        $this->assertSame([
            'defaults' => ['duration' => 5000],
            'max' => 5,
        ], $array);
    }
}
