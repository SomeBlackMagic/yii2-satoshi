<?php
declare(strict_types=1);

namespace SomeBlackMagic\Satoshi;

use Brick\Math\BigDecimal;

/**
 * Class SatoshiConverter
 * @package App\Components\Satoshi
 */
class SatoshiConverter
{
    public const SCALE = 8;

    /**
     * @param string $number
     *
     * @return Satoshi
     */
    public static function toSatoshi(string $number): Satoshi
    {
        $exponent = self::exponent($number, self::SCALE);

        $number = self::scale($exponent, self::SCALE);

        $result = BigDecimal::of($number)
            ->multipliedBy(self::getMultiplier())
            ->toScale(self::SCALE)
            ->toInt();

        return new Satoshi($result);
    }

    /**
     * @param Satoshi $satoshi
     *
     * @return string
     */
    public static function fromSatoshi(Satoshi $satoshi): string
    {

        $result = BigDecimal::of($satoshi->toInteger())
            ->dividedBy(self::getMultiplier(), self::SCALE)
            ->toScale(self::SCALE)
            ->toBigDecimal();

        return self::exponent((string)$result->toFloat(), self::SCALE);
    }

    /**
     * @return string
     */
    public static function getMultiplier(): string
    {
        return (string)bcpow('10', (string)self::SCALE);
    }

    /**
     * @param float|string $value
     * @param int|null $scale
     *
     * @return string
     */
    public static function exponent(string $value, int $scale): string
    {
        $split = explode('e', (string)$value);

        if (count($split) === 1) {
            $split = explode('E', (string)$value);
        }

        if (count($split) > 1) {
            $value = bcmul($split[0], bcpow('10', $split[1], $scale), $scale);
        }

        return $value;
    }

    /**
     * @param string $number
     * @param int $scale
     *
     * @return string
     */
    public static function scale(string $number, int $scale): string
    {
        return bcmul($number, '1', $scale);
    }
}
