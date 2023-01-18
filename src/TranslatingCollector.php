<?php declare(strict_types=1);

namespace MAS\Toaster;

use Illuminate\Contracts\Translation\Translator;

/** @internal */
final class TranslatingCollector implements Collector
{
    public function __construct(
        private readonly Collector $next,
        private readonly Translator $translator,
    ) {}

    public function add(Toast $toast): void
    {
        $replacement = $this->translator->get($original = $toast->message->value, $toast->message->replace);

        if (is_string($replacement) && $replacement !== $original) {
            $toast = $toast->clone($replacement);
        }

        $this->next->add($toast);
    }

    public function flush(): array
    {
        return $this->next->flush();
    }
}
