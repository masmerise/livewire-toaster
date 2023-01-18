<?php declare(strict_types=1);

namespace MAS\Toast;

enum Position: string
{
    case Center = 'center';
    case Left = 'left';
    case Right = 'right';

    public function isCenter(): bool
    {
        return $this === self::Center;
    }

    public function isLeft(): bool
    {
        return $this === self::Left;
    }

    public function isRight(): bool
    {
        return $this === self::Right;
    }
}
