<?php declare(strict_types=1);

namespace Masmerise\Toaster;

use Illuminate\Contracts\Translation\Translator;

/** @internal */
final readonly class TranslatingCollector implements Collector
{
    public function __construct(
        private Collector $next,
        private Translator $translator,
    ) {}

    public function collect(Toast $toast): void
    {
        $replacement = $this->translator->get($original = $toast->message->value, $toast->message->replace);

        if (is_string($replacement) && $replacement !== $original) {
            $toast = ToastBuilder::proto($toast)->message($replacement)->get();
        }

        $this->next->collect($toast);
    }

    public function release(): array
    {
        return $this->next->release();
    }
}
