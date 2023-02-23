<?php declare(strict_types=1);

namespace MAS\Toaster;

/** @internal */
final class AccessibleCollector implements Collector
{
    private const SECOND = 1000;
    private const WORDS = 100;

    public function __construct(
        private readonly Collector $next,
    ) {}

    public function collect(Toast $toast): void
    {
        $addend = floor(str_word_count($toast->message->value) / self::WORDS);
        $addend = intval($addend) * self::SECOND;

        if ($addend > 0) {
            $toast = ToastBuilder::proto($toast)->duration($toast->duration->value + $addend)->get();
        }

        $this->next->collect($toast);
    }

    public function release(): array
    {
        return $this->next->release();
    }
}
