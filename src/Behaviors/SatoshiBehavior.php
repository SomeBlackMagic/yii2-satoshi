<?php
declare(strict_types=1);

namespace SomeBlackMagic\Satoshi\Behaviors;

use SomeBlackMagic\Satoshi\Satoshi;
use Brick\Math\BigNumber;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\db\BaseActiveRecord;

/**
 * Class SatoshiBehavior
 * @package SomeBlackMagic\Satoshi\Behaviors
 */
class SatoshiBehavior extends Behavior
{
    
    /**
     * @var array
     */
    public $fields = [];
    
    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'objectConverter',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'objectMapper',
    
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'objectConverter',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'objectMapper',
    
            BaseActiveRecord::EVENT_AFTER_FIND => 'objectMapper',
    
            // can make problems
            BaseActiveRecord::EVENT_INIT => 'objectMapper',
        ];
    }
    
    /**
     *
     */
    public function objectMapper(): void
    {
        foreach ($this->owner->attributes as $attributeName => $value) {
            if (in_array($attributeName, $this->fields)) {
                try {
                    $integerValue = BigNumber::of($value)->toInt();
                    $this->owner->{$attributeName} = new Satoshi($integerValue);
                } catch (RoundingNecessaryException $e) {
                    throw new InvalidParamException('Attribute ' . $attributeName . ' value must be integer.');
                } catch (NumberFormatException $e) {
                    if ($value != null) {
                        throw new InvalidParamException('Attribute ' . $attributeName . ' value must be integer or null.');
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
            if (in_array($attributeName, $this->fields)) {
                $this->parseValue($attributeName, $value);
            }
        }
    }

    /**
     * @param $attributeName
     * @param $value
     */
    private function parseValue($attributeName, $value)
    {
        if ($value instanceof Satoshi) {
            /** @var \SomeBlackMagic\Satoshi\Satoshi $value */
            $this->owner->{$attributeName} = $value->toInteger();
        } else {
            if ($value !== null) {
                throw new InvalidParamException('Attribute ' . $attributeName . ' must be Satoshi or null.');
            }
        }
    }
}
