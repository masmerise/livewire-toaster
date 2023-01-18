<?php declare(strict_types=1);

namespace Tests;

use MAS\Toaster\Message;
use MAS\Toaster\TranslatingCollector;

final class TranslatingCollectorTest extends TestCase
{
    use CollectorFactoryMethods;
    use ToastFactoryMethods;

    /** @test */
    public function it_can_translate_the_messages(): void
    {
        $collector = new TranslatingCollector($this->aCollector(), $this->app['translator']);
        $message = Message::fromTranslatable('auth.throttle', ['seconds' => 1337]);

        $collector->add($this->aToast(message: $message));
        [$toast] = $collector->flush();

        $this->assertSame('Too many login attempts. Please try again in 1337 seconds.', $toast->message->value);
    }

    /** @test */
    public function it_doesnt_replace_array_resolved_translations(): void
    {
        $collector = new TranslatingCollector($this->aCollector(), $this->app['translator']);
        $message = Message::fromTranslatable('validation.size');

        $collector->add($this->aToast(message: $message));
        [$toast] = $collector->flush();

        $this->assertSame('validation.size', $toast->message->value);
    }

    /** @test */
    public function it_doesnt_modify_regular_strings(): void
    {
        $collector = new TranslatingCollector($this->aCollector(), $this->app['translator']);

        $collector->add($this->aToast());
        [$toast] = $collector->flush();

        $this->assertSame('Crispy toasts', $toast->message->value);
    }
}
