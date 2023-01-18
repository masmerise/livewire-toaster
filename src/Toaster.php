<?php declare(strict_types=1);

namespace MAS\Toast;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void add(Toast $toast)
 * @method static void assertDispatched()
 * @method static void assertNothingDispatched()
 */
final class Toaster extends Facade
{
    public static function error(string $message, array $replace = []): PendingToast
    {
        return self::toast()->message($message, $replace)->error();
    }

    public static function fake(): TestableCollector
    {
        self::swap($fake = new TestableCollector());

        return $fake;
    }

    public static function info(string $message, array $replace = []): PendingToast
    {
        return self::toast()->message($message, $replace)->info();
    }

    public static function success(string $message, array $replace = []): PendingToast
    {
        return self::toast()->message($message, $replace)->success();
    }

    public static function toast(): PendingToast
    {
        return PendingToast::make(self::$app[ToastConfig::class]->duration());
    }

    public static function warning(string $message, array $replace = []): PendingToast
    {
        return self::toast()->message($message, $replace)->warning();
    }

    protected static function getFacadeAccessor(): string
    {
        return ToastServiceProvider::NAME;
    }

    protected static function getMockableClass(): string
    {
        return Collector::class;
    }
}
