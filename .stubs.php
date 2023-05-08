<?php declare(strict_types=1);

namespace Illuminate\Http
{
    class RedirectResponse
    {
        public function error(string $message, array $replace = []): RedirectResponse {}

        public function info(string $message, array $replace = []): RedirectResponse {}

        public function success(string $message, array $replace = []): RedirectResponse {}

        public function warning(string $message, array $replace = []): RedirectResponse {}
    }
}

namespace Illuminate\Routing
{
    class Redirector
    {
        public function error(string $message, array $replace = []): Redirector {}

        public function info(string $message, array $replace = []): Redirector {}

        public function success(string $message, array $replace = []): Redirector {}

        public function warning(string $message, array $replace = []): Redirector {}
    }
}
