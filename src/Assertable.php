<?php declare(strict_types=1);

namespace Masmerise\Toaster;

/** @interal */
trait Assertable
{
    public function is(string $value): bool
    {
        return $value === $this->value;
    }
}
