<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ToastableMacrosTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->get('redirect', [ToastableMacroController::class, 'redirect']);
        $router->get('view', [ToastableMacroController::class, 'view']);
    }

    /** @test */
    public function redirect(): void
    {
        $this->get('redirect')->assertSessionHas('toasts');
    }

    /** @test */
    public function view(): void
    {
        $this->get('view')->assertSessionHas('toasts');
    }
}

final class ToastableMacroController
{
    public function redirect(): RedirectResponse
    {
        return redirect()
            ->away('https://www.google.com')
            ->success('Success Macro!');
    }

    public function view(): View
    {
        return view('toast::hub')->error('Error Macro!');
    }
}
