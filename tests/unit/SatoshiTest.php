<?php
declare(strict_types=1);

namespace SomeBlackMagic\Satoshi\Tests\Unit;

use SomeBlackMagic\Satoshi\Satoshi;
use SomeBlackMagic\Satoshi\SatoshiConverter;
use SomeBlackMagic\Satoshi\SatoshiMath;
use PHPUnit\Framework\TestCase;

/**
 * Class SatoshiTest
 * @package tests\unit\components
 */
class SatoshiTest extends TestCase
{

    /**
     * @test
     */
    public function satoshi_convert_library_correct_convert_floats_to_double()
    {
        $amount = '206.16978521';
        $convertedAmount = SatoshiConverter::toSatoshi($amount);

        $this->assertInstanceOf(Satoshi::class, $convertedAmount);
        $this->assertEquals($convertedAmount->toInteger(), 20616978521);
    }


    /**
     * @test
     */
    public function satoshi_convert_library_correct_convert_big_floats_to_double()
    {
        $amount = '99999999.99999989';
        $convertedAmount = SatoshiConverter::toSatoshi($amount);

        $this->assertInstanceOf(Satoshi::class, $convertedAmount);
        $this->assertEquals($convertedAmount->toInteger(), 9999999999999989);
    }

    /**
     * @test
     */
    public function satoshi_convert_library_correct_convert_small_floats_to_double()
    {
        $amount = '0.00000001';
        $convertedAmount = SatoshiConverter::toSatoshi($amount);

        $this->assertInstanceOf(Satoshi::class, $convertedAmount);
        $this->assertEquals($convertedAmount->toInteger(), 1);
    }

    /**
     * @test
     */
    public function satoshi_convert_library_correct_convert_satoshi_to_float()
    {
        $amount = new Satoshi(20616978521);
        $convertedAmount = SatoshiConverter::fromSatoshi($amount);

        $this->assertEquals($convertedAmount, '206.16978521');
    }

    /**
     * @test
     */
    public function satoshi_object_correct_return_integer()
    {
        $satoshi = new Satoshi(20616978521);
        $this->assertEquals($satoshi->toInteger(), 20616978521);
    }

    /**
     * @test
     */
    public function satoshi_object_correct_return_float()
    {
        $satoshi = new Satoshi(20616978521);
        $this->assertEquals($satoshi->toFloat(), 206.16978521);
    }

    /**
     * @test
     */
    public function satoshi_math_library_for_add_function()
    {
        $x = SatoshiConverter::toSatoshi('206.16978521');
        $y = SatoshiConverter::toSatoshi('10');

        $result = SatoshiMath::add($x, $y);

        $this->assertInstanceOf(Satoshi::class, $result);
        $this->assertEquals($result->toInteger(), 21616978521);
    }

    /**
     * @test
     */
    public function satoshi_math_library_for_sub_function()
    {
        $x = SatoshiConverter::toSatoshi('206.16978521');
        $y = SatoshiConverter::toSatoshi('10');

        $result = SatoshiMath::sub($x, $y);

        $this->assertInstanceOf(Satoshi::class, $result);
        $this->assertEquals($result->toInteger(), 19616978521);
    }


    /**
     * @test
     */
    public function satoshi_math_library_for_percent_function()
    {
        $x = SatoshiConverter::toSatoshi('101.55826223');

        $result = SatoshiMath::percent($x, '5.7');

        $this->assertInstanceOf(Satoshi::class, $result);
        $this->assertEquals($result->toFloat(), '5.78882095');
    }

    /**
     * @test
     */
    public function satoshi_math_library_for_divide_function()
    {
        $this->markTestSkipped();
//        $x = SatoshiConverter::toSatoshi('10.1');
//
//        $result = SatoshiMath::divide($x, SatoshiConverter::toSatoshi('3'));
//
//        $this->assertInstanceOf(Satoshi::class, $result);
//        $this->assertEquals($result, SatoshiConverter::toSatoshi('3.366666666'));
    }

    /**
     * @test
     */
    public function satoshi_math_library_for_divide_function_with_minimal_value()
    {
        $x = SatoshiConverter::toSatoshi('0.00000001');

        $result = SatoshiMath::divide($x, SatoshiConverter::toSatoshi('2.0'));

        $this->assertInstanceOf(Satoshi::class, $result);
        $this->assertEquals($result, SatoshiConverter::toSatoshi('0.0000000'));
    }

    /**
     * @test
     */
    public function satoshi_math_library_for_multiple_function()
    {
        $x = SatoshiConverter::toSatoshi('12.11111111');

        $result = SatoshiMath::multiple($x, SatoshiConverter::toSatoshi('1.01'));

        $this->assertInstanceOf(Satoshi::class, $result);
        $this->assertEquals($result, SatoshiConverter::toSatoshi('12.23222222'));
    }

    /**
     * @test
     */
    public function satoshi_math_library_for_multiple_function_with_max_value()
    {
        $x = SatoshiConverter::toSatoshi('92233720368.49999999');

        $result = SatoshiMath::multiple($x, SatoshiConverter::toSatoshi('1.0000000'));

        $this->assertInstanceOf(Satoshi::class, $result);
        $this->assertEquals($result, SatoshiConverter::toSatoshi('92233720368.49999999'));
    }

    /**
     * @test
     */
    public function satoshi_math_library_for_multiple_function_with_big_value()
    {
        $x = SatoshiConverter::toSatoshi('46111111111.00000001');

        $result = SatoshiMath::multiple($x, SatoshiConverter::toSatoshi('2.00000001'));

        $this->assertInstanceOf(Satoshi::class, $result);
        $this->assertEquals($result, SatoshiConverter::toSatoshi('92222222683.11111113'));
    }

}
