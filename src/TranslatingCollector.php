<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Contracts\Translation\Translator;

/** @internal */
final readonly class TranslatingCollector implements Collector
{
    public function __construct(
        private Collector $next,
        private Translator $translator,
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
