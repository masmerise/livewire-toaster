<?php declare(strict_types=1);

namespace Tests;

use MAS\Toaster\Duration;
use MAS\Toaster\Message;
use MAS\Toaster\Toast;
use MAS\Toaster\ToastType;

trait ToastFactoryMethods
{
    private function aToast(...$values): Toast
    {
        return new Toast(...[
            'message' => Message::fromString('Crispy toasts'),
            'duration' => Duration::fromMillis(1000),
            'type' => ToastType::Success,
            ...$values,
        ]);
    }
}
