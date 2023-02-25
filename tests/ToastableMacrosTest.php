<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Http\RedirectResponse;
use PHPUnit\Framework\Attributes\Test;

final class ToastableMacrosTest extends TestCase
{
    protected function defineRoutes($router): void
    {
        $router->get('redirect', [ToastableMacroController::class, 'redirect'])->middleware('web');
    }

    #[Test]
    public function redirect(): void
    {
        $this->get('redirect')->assertSessionHas('toasts');
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
}
