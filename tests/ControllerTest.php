<?php declare(strict_types=1);

namespace Tests;

use MAS\Toast\Collector;
use MAS\Toast\Toaster;
use MAS\Toast\Toastable;
use MAS\Toast\ToastBuilder;

final class ControllerTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->get('inject', [ToastController::class, 'inject']);
        $router->get('multiple', [ToastController::class, 'multiple']);
    }

    /** @test */
    public function multiple_toasts_can_be_dispatched(): void
    {
        $this->get('multiple')->assertOk()->assertSessionHas('toasts', [
            [
                'duration' => 5000,
                'message' => 'The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk',
                'type' => 'warning',
            ],
            [
                'duration' => 3333,
                'message' => 'Life is available only in the present moment. - Thich Nhat Hanh',
                'type' => 'error',
            ]
        ]);
    }

    /** @test */
    public function toast_is_flashed_to_the_session_using_dependency_injection(): void
    {
        $this->get('inject')->assertOk()->assertSessionHas('toasts', [[
            'duration' => 2000,
            'message' => 'The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk',
            'type' => 'success',
        ]]);
    }
}

final class ToastController
{
    use Toastable;

    public function inject(Collector $toasts): array
    {
        $toast = ToastBuilder::create()
            ->success()
            ->duration(2000)
            ->message('The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk')
            ->get();

        $toasts->add($toast);

        return ['message' => 'ok'];
    }

    public function multiple(): array
    {
        $this
            ->toast()
            ->message('The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk')
            ->warning();

        Toaster::toast()
            ->duration(3333)
            ->message('Life is available only in the present moment. - Thich Nhat Hanh')
            ->error()
            ->dispatch();

        return ['message' => 'ok'];
    }
}
