<?php declare(strict_types=1);

namespace Tests;

use MAS\Toast\ToastBuilder;
use MAS\Toast\ToastType;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class ToastBuilderTest extends TestCase
{
    /** @test */
    public function it_can_fluently_build_and_return_a_toast(): void
    {
        $builderA = ToastBuilder::create();
        $builderB = $builderA->message('Rice cooker');
        $builderC = $builderB->success();
        $builderD = $builderC->duration(4000);

        $toast = $builderD->get();

        $this->assertNotSame($builderA, $builderB);
        $this->assertNotSame($builderB, $builderC);
        $this->assertNotSame($builderC, $builderD);
        $this->assertSame('Rice cooker', $toast->message->value);
        $this->assertSame(ToastType::Success, $toast->type);
        $this->assertSame(4000, $toast->duration->value);
    }

    /** @test */
    public function it_throws_if_the_builder_data_is_incomplete_to_build_a_toast(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('You must provide a valid duration.');

        ToastBuilder::create()->get();
    }
}
