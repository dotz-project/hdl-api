<?php

namespace app\components;


use yii\i18n\Formatter as BaseFormatter;

class Formatter extends BaseFormatter
{
    public function asHex($value, $format = null)
    {
        return strtoupper(bin2hex($value));
    }

}