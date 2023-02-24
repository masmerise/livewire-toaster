<?php declare(strict_types=1);

namespace Tests;

use InvalidArgumentException;
use Masmerise\Toaster\Message;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{
    /** @test */
    public function it_requires_a_message_not_to_be_empty(): void
    {
        $message = Message::fromTranslatable('Clementine :name', ['name' => 'Tangerine']);
        $this->assertSame('Clementine :name', $message->value);
        $this->assertSame(['name' => 'Tangerine'], $message->replace);

        $this->expectExceptionMessage(InvalidArgumentException::class);
        $this->expectExceptionMessage('The message value cannot be empty.');

        Message::fromString('     ');
    }

    /** @test */
    public function it_is_json_serializable(): void
    {
        $message = Message::fromString('    Clementine     ');

        $result = json_decode(json_encode($message, JSON_THROW_ON_ERROR));

        $this->assertSame('Clementine', $result);
    }
}
