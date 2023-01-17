<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class ToastTest extends TestCase
{
    use ToastFactoryMethods;

    /** @test */
    public function it_can_be_serialized_to_array(): void
    {
        $toast = $this->aToast();

        $result = $toast->toArray();

        $this->assertSame([
            'duration' => 1000,
            'message' => 'Crispy toasts',
            'position' => 'right',
            'type' => 'success',
        ], $result);
    }
}
