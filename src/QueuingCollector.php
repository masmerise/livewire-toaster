<?php declare(strict_types=1);

namespace MAS\Toast;

/** @internal */
final class QueuingCollector implements Collector
{
    /** @var array<Message> */
    private array $messages = [];

    public static function make(): self
    {
        return new self();
    }

    public function flush(): array
    {
        $messages = $this->messages;
        $this->messages = [];

        return $messages;
    }
}
