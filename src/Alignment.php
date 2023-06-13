<?php declare(strict_types=1);

namespace Masmerise\Toaster;

/** @internal */
enum Alignment: string
{
    use Assertable;

    case Bottom = 'bottom';
    case Middle = 'middle';
    case Top = 'top';
}
