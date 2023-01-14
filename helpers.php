<?php declare(strict_types=1);

use MAS\Toast\Collector;
use MAS\Toast\ToastServiceProvider;

if (! function_exists('toast')) {
    function toast(): Collector
    {
        return app(ToastServiceProvider::NAME);
    }
}
