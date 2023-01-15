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

        $json = json_encode($toast, JSON_THROW_ON_ERROR);

        $this->assertSame([
            'message' => 'Crispy toasts',
            'duration' => 1000,
            'position' => 'right',
            'type' => 'success',
        ], json_decode($json, true));
    }
}
