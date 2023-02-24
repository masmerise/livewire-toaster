<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

/** @internal */
final readonly class SessionRelay
{
    public const NAME = 'toasts';

    public function __construct(private Application $app) {}

    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if (! $this->app->resolved(Collector::class)) {
            return $response;
        }

        if ($toasts = $this->app[Collector::class]->release()) {
            $this->app[Session::class]->put(self::NAME, $this->serialize($toasts));
        }

        return $response;
    }

    private function serialize(array $toasts): array
    {
        return array_map(static fn (Toast $toast) => $toast->toArray(), $toasts);
    }
}
