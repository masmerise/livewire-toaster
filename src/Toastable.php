<?php declare(strict_types=1);

namespace MAS\Toast;

trait Toastable
{
    protected function error(string $message, array $replace = []): PendingToast
    {
        return Toast::error($message, $replace);
    }

    protected function info(string $message, array $replace = []): PendingToast
    {
        return Toast::info($message, $replace);
    }

    protected function success(string $message, array $replace = []): PendingToast
    {
        return Toast::success($message, $replace);
    }

    protected function toast(): PendingToast
    {
        return Toast::create();
    }

    protected function warning(string $message, array $replace = []): PendingToast
    {
        return Toast::success($message, $replace);
    }
}
