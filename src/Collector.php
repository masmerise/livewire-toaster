<?php declare(strict_types=1);

namespace MAS\Toast;

interface Collector
{
    /**
     * @internal
     *
     * @return array<Message>
     */
    public function flush(): array;
}
