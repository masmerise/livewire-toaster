<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

final class ToastTest extends TestCase
{
    use ToastFactoryMethods;

    /** @test */
    public function it_can_be_serialized_to_json(): void
    {
        $toast = $this->aToast();

        $result = json_decode(json_encode($toast, JSON_THROW_ON_ERROR), true);

        $this->assertSame([
            'duration' => 1000,
            'message' => 'Crispy toasts',
            'position' => 'right',
            'type' => 'success',
        ], $result);
    }
}
