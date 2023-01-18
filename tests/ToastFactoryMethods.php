<?php declare(strict_types=1);

namespace Tests;

use MAS\Toast\Duration;
use MAS\Toast\Message;
use MAS\Toast\Toast;
use MAS\Toast\ToastType;

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
