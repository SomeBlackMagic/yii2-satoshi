<?php
declare(strict_types=1);

namespace SomeBlackMagic\Satoshi\Behaviors;

use Brick\Math\BigNumber;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use SomeBlackMagic\Satoshi\Satoshi;
use yii\base\Behavior;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Class SatoshiBehavior
 * @package SomeBlackMagic\Satoshi\Behaviors
 */
class SatoshiBehavior extends Behavior
{
    /**
     * @var ActiveRecord|null the owner of this behavior
     */
    public $owner;

    /**
     * @var array
     */
    public $fields = [];

    /**
     * @return array
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'objectConverter',
            BaseActiveRecord::EVENT_AFTER_INSERT  => 'objectMapper',

            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'objectConverter',
            BaseActiveRecord::EVENT_AFTER_UPDATE  => 'objectMapper',

            BaseActiveRecord::EVENT_AFTER_FIND => 'objectMapper',

        ];
    }

    /**
     *
     */
    public function objectMapper(): void
    {
        foreach ($this->owner->attributes as $attributeName => $value) {
            if (in_array($attributeName, $this->fields, true)) {
                try {
                    $integerValue = BigNumber::of($value)->toInt();
                    $this->owner->{$attributeName} = new Satoshi($integerValue);
                } catch (RoundingNecessaryException $e) {
                    throw new InvalidArgumentException('Attribute ' . $attributeName . ' value must be integer.');
                } catch (NumberFormatException $e) {
                    if ($value !== null) {
                        throw new InvalidArgumentException('Attribute ' . $attributeName . ' value must be integer or null.');
                    }
                }
            }
        }
    }

    /**
     *
     */
    public function objectConverter(): void
    {
        foreach ($this->owner->attributes as $attributeName => $value) {
            if (in_array($attributeName, $this->fields, true)) {
                $this->parseValue($attributeName, $value);
            }
        }
    }

    /**
     * @param $attributeName
     * @param $value
     */
    private function parseValue($attributeName, $value): void
    {
        if ($value instanceof Satoshi) {
            /** @var Satoshi $value */
            $this->owner->{$attributeName} = $value->toInteger();
        } elseif ($value !== null) {
            throw new InvalidArgumentException('Attribute ' . $attributeName . ' must be Satoshi or null.');
        }
    }
}
