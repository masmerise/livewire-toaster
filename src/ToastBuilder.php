<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Support\Traits\Conditionable;
use UnexpectedValueException;

final class ToastBuilder
{
    use Conditionable;

    private ?Duration $duration = null;

    private ?Message $message = null;

    private ?Position $position = null;

    private ?ToastType $type = null;

    public static function create(): self
    {
        return new self();
    }

    public function center(): self
    {
        $this->position = Position::Center;

        return $this;
    }

    public function duration(int $milliseconds): self
    {
        $this->duration = Duration::fromMillis($milliseconds);

        return $this;
    }

    public function error(): self
    {
        $this->type = ToastType::Error;

        return $this;
    }

    public function info(): self
    {
        $this->type = ToastType::Info;

        return $this;
    }

    public function left(): self
    {
        $this->position = Position::Left;

        return $this;
    }

    public function message(string $message): self
    {
        $this->message = Message::fromString($message);

        return $this;
    }

    public function right(): self
    {
        $this->position = Position::Right;

        return $this;
    }

    public function success(): self
    {
        $this->type = ToastType::Success;

        return $this;
    }

    public function warning(): self
    {
        $this->type = ToastType::Warning;

        return $this;
    }

    public function dispatch(): void
    {
        if (! $this->duration instanceof Duration) {
            $this->duration = Duration::fromMillis(config('toast.duration'));
        }

        if (! $this->position instanceof Position) {
            $this->position = Position::from(config('toast.position'));
        }

        app(Collector::class)->add($this->get());
    }

    public function get(): Toast
    {
        if (! $this->duration instanceof Duration) {
            throw new UnexpectedValueException('You must provide a valid duration.');
        }

        if (! $this->message instanceof Message) {
            throw new UnexpectedValueException('You must provide a valid message.');
        }

        if (! $this->position instanceof Position) {
            throw new UnexpectedValueException('You must choose a valid position.');
        }

        if (! $this->type instanceof ToastType) {
            throw new UnexpectedValueException('You must choose a valid type.');
        }

        return new Toast($this->message, $this->duration, $this->position, $this->type);
    }
}
