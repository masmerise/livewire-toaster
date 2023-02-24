<?php declare(strict_types=1);

namespace Tests;

use Masmerise\Toaster\Duration;
use Masmerise\Toaster\Message;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\ToastType;

trait ToastFactoryMethods
{
    private function aToast(...$values): Toast
    {
        return new Toast(...[
            'message' => Message::fromString('Crispy toasts'),
            'duration' => Duration::fromMillis(3000),
            'type' => ToastType::Success,
            ...$values,
        ]);
    }
}
