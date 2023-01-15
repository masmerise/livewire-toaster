<?php declare(strict_types=1);

namespace MAS\Toast;

use JsonSerializable;

enum Position: string implements JsonSerializable
{
    case Center = 'center';
    case Left = 'left';
    case Right = 'right';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
