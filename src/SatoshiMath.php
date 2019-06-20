<?php
declare(strict_types=1);

namespace SomeBlackMagic\Satoshi;

use Brick\Math\BigInteger;
use Brick\Math\BigRational;
use Brick\Math\RoundingMode;

/**
 * Class SatoshiMath
 * @package App\Components\Satoshi
 */
class SatoshiMath extends SatoshiConverter
{
    /**
     * @param Satoshi $satoshi
     * @param string $percent
     *
     * @return Satoshi
     */
    public static function percent(Satoshi $satoshi, string $percent): Satoshi
    {
        $percentSatoshi = self::toSatoshi($percent);
        $hundred = self::toSatoshi('100');

        $result = BigInteger::of($satoshi->toInteger())
            ->multipliedBy($percentSatoshi->toInteger())
            ->dividedBy($hundred->toInteger(), self::SCALE)
            ->toScale(self::SCALE)
            ->toInt();

        return new Satoshi($result);
    }

    /**
     * @param Satoshi $x
     * @param Satoshi $y
     *
     * @return Satoshi
     */
    public static function add(Satoshi $x, Satoshi $y): Satoshi
    {
        $result = BigInteger::of($x->toInteger())
            ->plus($y->toInteger())
            ->toScale(self::SCALE)
            ->toInt();

        return new Satoshi($result);
    }

    /**
     * @param Satoshi $x
     * @param Satoshi $y
     *
     * @return Satoshi
     */
    public static function sub(Satoshi $x, Satoshi $y): Satoshi
    {
        $result = BigInteger::of($x->toInteger())
            ->minus($y->toInteger())
            ->toScale(self::SCALE)
            ->toInt();

        return new Satoshi($result);
    }

    /**
     * @param Satoshi $x
     * @param Satoshi $divider
     * @param int $roundingMode
     *
     * @return Satoshi
     */
    public static function divide(Satoshi $x, Satoshi $divider, int $roundingMode = RoundingMode::DOWN): Satoshi
    {
        $result = BigRational::of($x->toFloat())
            ->dividedBy($divider->toFloat())
            ->toScale(self::SCALE, $roundingMode)
            ->getUnscaledValue()
            ->toInt();

        return new Satoshi($result);
    }

    /**
     * @param Satoshi $x
     * @param Satoshi $y
     *
     * @return Satoshi
     * @internal in new release will be renamed to 'multiple'
     */
    public static function pow(Satoshi $x, Satoshi $y): Satoshi
    {
        $result = BigInteger::of($x->toInteger())
            ->multipliedBy($y->toInteger())
            ->toScale(self::SCALE)
            ->toInt();

        return new Satoshi($result);
    }

    /**
     * @param Satoshi $x
     * @param Satoshi $y
     *
     * @return Satoshi
     */
    public static function multiple(Satoshi $x, Satoshi $y): Satoshi
    {
        $result = BigInteger::of($x->toInteger())
            ->multipliedBy($y->toInteger())
            ->dividedBy(10 ** self::SCALE, RoundingMode::DOWN)
            ->toScale(self::SCALE)
            ->toInt();

        return new Satoshi($result);
    }
}
