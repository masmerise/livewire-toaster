<?php declare(strict_types=1);

namespace MAS\Toast;

interface Collector
{
    public function add(Toast $toast): void;

    /**
     * @internal
     *
     * @return array<Toast>
     */
    public function flush(): array;
}
