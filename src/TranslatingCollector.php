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
        $message = $this->translator->get($toast->message->value);

        if (is_string($message)) {
            $toast = $toast->clone($message);
        }

        $this->next->add($toast);
    }

    public function flush(): array
    {
        return $this->next->flush();
    }
}
