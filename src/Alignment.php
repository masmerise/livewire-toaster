<?php declare(strict_types=1);

namespace Masmerise\Toaster;

enum Alignment: string
{
    use Assertable;

    case Bottom = 'bottom';
    case Top = 'top';
}
