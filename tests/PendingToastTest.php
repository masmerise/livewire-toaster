<?php declare(strict_types=1);

namespace Tests;

use MAS\Toaster\Toaster;

final class PendingToastTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_through_static_factory_on_toaster(): void
    {
        $pending = Toaster::toast()->message('lol')->error();

        $toast = $pending->get();

        $this->assertSame([
            'duration' => 5000, // config default
            'message' => 'lol',
            'type' => 'error',
        ], $toast->toArray());
    }

    /** @test */
    public function it_can_be_instantiated_thru_magic_methods_on_toast(): void
    {
        $error = Toaster::error('validation.accepted', ['attribute' => 'terms'])->get();
        $this->assertSame([
            'duration' => 5000,
            'message' => 'validation.accepted',
            'type' => 'error',
        ], $error->toArray());

        $info = Toaster::info('Informational')->duration(1500)->get();
        $this->assertSame([
            'duration' => 1500,
            'message' => 'Informational',
            'type' => 'info',
        ], $info->toArray());

        $success = Toaster::success('Successful')->get();
        $this->assertSame([
            'duration' => 5000,
            'message' => 'Successful',
            'type' => 'success',
        ], $success->toArray());

        $warning = Toaster::warning('passwords.reset')->get();
        $this->assertSame([
            'duration' => 5000,
            'message' => 'passwords.reset',
            'type' => 'warning',
        ], $warning->toArray());
    }

    /** @test */
    public function it_will_automatically_dispatch_the_toast_upon_destruction(): void
    {
        Toaster::shouldReceive('add')->once();

        Toaster::error('Uvuvuvwevwe Onyetenyevwe Ughemuhwem Osas');
    }

    /** @test */
    public function it_will_only_dispatch_once(): void
    {
        Toaster::shouldReceive('add')->once();

        Toaster::success('OK!')->dispatch();
    }
}
