<?php declare(strict_types=1);

namespace Tests;

use Masmerise\Toaster\Message;
use Masmerise\Toaster\TranslatingCollector;

final class TranslatingCollectorTest extends TestCase
{
    use CollectorFactoryMethods;
    use ToastFactoryMethods;

    /** @test */
    public function it_can_translate_the_messages(): void
    {
        $collector = new TranslatingCollector($this->aCollector(), $this->app['translator']);
        $message = Message::fromTranslatable('auth.throttle', ['seconds' => 1337]);

        $collector->collect($this->aToast(message: $message));
        [$toast] = $collector->release();

        $this->assertSame('Too many login attempts. Please try again in 1337 seconds.', $toast->message->value);
    }

    /** @test */
    public function it_doesnt_replace_array_resolved_translations(): void
    {
        $collector = new TranslatingCollector($this->aCollector(), $this->app['translator']);
        $message = Message::fromTranslatable('validation.size');

        $collector->collect($this->aToast(message: $message));
        [$toast] = $collector->release();

        $this->assertSame('validation.size', $toast->message->value);
    }

    /** @test */
    public function it_doesnt_modify_regular_strings(): void
    {
        $collector = new TranslatingCollector($this->aCollector(), $this->app['translator']);

        $collector->collect($this->aToast());
        [$toast] = $collector->release();

        $this->assertSame('Crispy toasts', $toast->message->value);
    }
}
