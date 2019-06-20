<?php
declare(strict_types=1);

namespace SomeBlackMagic\Satoshi\Behaviors;

use SomeBlackMagic\Satoshi\SatoshiConverter;
use Yii;
use yii\base\Behavior;
use yii\base\Model;
use yii\db\ActiveRecord;


/**
 * Class SatoshiBehavior
 * @package App\Components\Satoshi
 */
class FloatToSatoshiBehavior extends Behavior
{
    /**
     * @var ActiveRecord|null the owner of this behavior
     */
    public $owner;

    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @return array
     */
    public function events()
    {
        return [
            Model::EVENT_AFTER_VALIDATE => 'decodeAttributes',
        ];
    }

    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                Model::EVENT_AFTER_VALIDATE => $this->attributes,
            ];
        }
    }

    public function decodeAttributes(): void
    {

        foreach ($this->attributes as $attribute) {
            if ($this->owner->$attribute !== null) {
                $this->owner->setAttributes([$attribute => SatoshiConverter::toSatoshi($this->owner->$attribute)]);
            } elseif (YII_DEBUG) {
                Yii::debug('Field "' . $attribute . '" is null ', get_class($this->owner));
            }
        }
    }


}
