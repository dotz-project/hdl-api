<?php

namespace app\behaviors;

use yii\base\Behavior;
use app\modules\apiv1\helpers\Uuid;

class UUIDBehavior extends Behavior
{

    public $column = 'id';
    public $uuidStrategy = null;

    public function events()
    {
        return[
            \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'beforeCreate',
        ];
    }

    public function beforeCreate()
    {
        if(empty($this->owner->{$this->column}))
            $this->owner->{$this->column} = $this->createUUID();
    }

    public function createUUID()
    {
        return Uuid::uuid($this->uuidStrategy);
    }

}