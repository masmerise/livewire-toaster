<?php declare(strict_types=1);

namespace MAS\Toast;

enum MessageType: string
{
    case Error = 'error';
    case Info = 'info';
    case Success = 'success';
    case Warning = 'warning';
}
