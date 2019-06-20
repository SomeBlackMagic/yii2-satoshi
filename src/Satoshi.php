<?php
declare(strict_types=1);

namespace SomeBlackMagic\Satoshi;

/**
 * Class Satoshi
 * @package App\Components\Satoshi
 */
class Satoshi
{
    public $satoshi;

    /**
     * Satoshi constructor.
     *
     * @param int $satoshi
     */
    public function __construct(int $satoshi)
    {
        $this->satoshi = $satoshi;
    }

    /**
     * @return string
     */
    public function toFloat(): string
    {
        return SatoshiConverter::fromSatoshi($this);
    }

    /**
     * @return int
     */
    public function toInteger(): int
    {
        return $this->satoshi;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return number_format($this->toFloat(), 8, '.', '');
    }
}
