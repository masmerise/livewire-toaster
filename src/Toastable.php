<?php declare(strict_types=1);

namespace Masmerise\Toaster;

trait Toastable
{
    protected function error(string $message, array $replace = []): PendingToast
    {
        return Toaster::error($message, $replace);
    }

    protected function info(string $message, array $replace = []): PendingToast
    {
        return Toaster::info($message, $replace);
    }

    protected function success(string $message, array $replace = []): PendingToast
    {
        return Toaster::success($message, $replace);
    }

    protected function toast(): PendingToast
    {
        return Toaster::toast();
    }

    protected function warning(string $message, array $replace = []): PendingToast
    {
        return Toaster::warning($message, $replace);
    }
}
