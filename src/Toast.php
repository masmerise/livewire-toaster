<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Support\Facades\Facade;

final class Toast extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ToastServiceProvider::NAME;
    }
}
