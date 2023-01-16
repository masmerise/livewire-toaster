<?php declare(strict_types=1);

namespace Tests;

use BadMethodCallException;
use MAS\Toast\Toast;
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

    /**
     * @dataProvider calls
     *
     * @test
     */
    public function it_throws_bad_method_exceptions_for_wrong_magic_static_calls(string $name, array $arguments = []): void
    {
        $this->expectException(BadMethodCallException::class);

        Toast::__callStatic($name, $arguments);
    }

    private function calls(): array
    {
        return [
            ['random'],
            ['infoo', ['message']],
            ['success'],
            ['error', []],
            ['warning', ['message', [], 'bruh']],
        ];
    }
}
