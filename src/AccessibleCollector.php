<?php declare(strict_types=1);

namespace Masmerise\Toaster;

/** @internal */
final readonly class AccessibleCollector implements Collector
{
    private const AMOUNT_OF_WORDS = 100;
    private const ONE_SECOND = 1000;

    public function __construct(private Collector $next) {}

    public function collect(Toast $toast): void
    {
        $addend = (int) floor(str_word_count($toast->message->value) / self::AMOUNT_OF_WORDS);
        $addend = $addend * self::ONE_SECOND;

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
