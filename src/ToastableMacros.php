<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use Closure;

/** @internal */
final class ToastableMacros
{
    protected function error(): Closure
    {
        return $this->macro('error');
    }

    protected function info(): Closure
    {
        return $this->macro('info');
    }

    protected function success(): Closure
    {
        return $this->macro('success');
    }

    protected function warning(): Closure
    {
        return $this->macro('warning');
    }

    private function macro(string $type): Closure
    {
        return function (string $message, array $replace = []) use ($type) {
            Toaster::toast()->type($type)->message($message, $replace);

            return $this;
        };
    }
}
