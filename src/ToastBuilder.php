<?php declare(strict_types=1);

namespace MAS\Toast;

use UnexpectedValueException;

final class ToastBuilder
{
    private ?Duration $duration = null;

    private ?Message $message = null;

    private ?ToastType $type = null;

    public static function create(): self
    {
        return new self();
    }

    public function duration(int $milliseconds): self
    {
        $this->duration = new Duration($milliseconds);

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

    public function message(string $message): self
    {
        $this->message = new Message($message);

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
            $this->duration(config('toast.duration')); // @phpstan-ignore-line
        }

        app(ToastServiceProvider::NAME)->add($this->get());
    }

    public function get(): Toast
    {
        if (! $this->duration instanceof Duration) {
            throw new UnexpectedValueException('You must provide a valid duration to create a Toast.');
        }

        if (! $this->message instanceof Message) {
            throw new UnexpectedValueException('You must provide a valid message to create a Toast.');
        }

        if (! $this->type instanceof ToastType) {
            throw new UnexpectedValueException('You must choose a valid type to create a Toast.');
        }

        return new Toast($this->message, $this->duration, $this->type);
    }
}
