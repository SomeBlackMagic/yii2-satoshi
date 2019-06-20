<?php
declare(strict_types=1);

namespace SomeBlackMagic\Satoshi\Validators;

use SomeBlackMagic\Satoshi\Satoshi;
use Yii;
use yii\base\Model;
use yii\validators\Validator;

/**
 * Class SatoshiValidator
 * @package SomeBlackMagic\Satoshi\Validators
 */
class SatoshiValidator extends Validator
{
    /**
     * @param Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        if (!$model->$attribute instanceof Satoshi) {
            $this->addError($model, $attribute, Yii::t('yii', '{attribute} must be a Satoshi.'));
        }
    }
}
