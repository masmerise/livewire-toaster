<?php declare(strict_types=1);

namespace Masmerise\Toaster;

enum Position: string
{
    case Center = 'center';
    case Left = 'left';
    case Right = 'right';

    public function is(string $position): bool
    {
        return $position === $this->value;
    }
}
