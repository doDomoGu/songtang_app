<?php

namespace yun\models;
use Yii;

class Attribute extends \yii\db\ActiveRecord
{
    const TYPE_AREA = 1;
    const TYPE_BUSINESS = 2;


    const AREA_DEFAULT = 1;
    const BUSINESS_DEFAULT = 1;
}