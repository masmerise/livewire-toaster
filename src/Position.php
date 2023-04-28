<?php declare(strict_types=1);

namespace Masmerise\Toaster;

/** @internal */
enum Position: string
{
    use Assertable;

    case Center = 'center';
    case Left = 'left';
    case Right = 'right';
}
