<?php declare(strict_types=1);

namespace Tests;

use MAS\Toast\Duration;
use MAS\Toast\Message;
use MAS\Toast\Position;
use MAS\Toast\Toast;
use MAS\Toast\ToastType;

trait ToastFactoryMethods
{
    private function aToast(): Toast
    {
        return new Toast(
            new Message('Crispy toasts'),
            new Duration(1000),
            Position::Right,
            ToastType::Success
        );
    }
}
