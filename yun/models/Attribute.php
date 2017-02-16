<?php

namespace yun\models;
use Yii;

class Attribute extends \yii\db\ActiveRecord
{
    const TYPE_DISTRICT = 1;
    const TYPE_INDUSTRY = 2;
    //const TYPE_COMPANY  = 3;


    const DISTRICT_DEFAULT = 1;
    const INDUSTRY_DEFAULT = 1;
}