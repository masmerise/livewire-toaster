<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ToastTest extends TestCase
{
    use ToastFactoryMethods;

    #[Test]
    public function it_can_be_serialized_to_array(): void
    {
        $toast = $this->aToast();

        $result = $toast->toArray();

        $this->assertSame([
            'duration' => 3000,
            'message' => 'Crispy toasts',
            'type' => 'success',
        ], $result);
    }
}
