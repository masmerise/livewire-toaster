<?php declare(strict_types=1);

namespace Tests;

use InvalidArgumentException;
use Masmerise\Toaster\Message;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{
    #[Test]
    public function it_requires_a_message_not_to_be_empty(): void
    {
        $message = Message::fromTranslatable('Clementine :name', ['name' => 'Tangerine']);
        $this->assertSame('Clementine :name', $message->value);
        $this->assertSame(['name' => 'Tangerine'], $message->replace);

        $this->expectExceptionMessage(InvalidArgumentException::class);
        $this->expectExceptionMessage('The message value cannot be empty.');

        Message::fromString('     ');
    }
}
