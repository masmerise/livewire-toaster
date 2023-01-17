<?php declare(strict_types=1);

namespace MAS\Toast;

interface Collector
{
    public function add(Toast $toast): void;

    /** @internal */
    public function flush(): array;
}
