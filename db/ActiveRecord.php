<?php

namespace app\db;

use Ramsey\Uuid\Uuid;
use Yii;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public static function primaryKey(){
        return ['id'];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(empty($this->id))
                $this->id = $this->createUUID();
            return true;
        }
        return false;
    }

    public function createUUID()
    {
        return (string) Uuid::uuid4();
    }
}