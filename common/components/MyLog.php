<?php
namespace common\components;

use yii\base\Component;
use Yii;
use yii\log\Logger;

class MyLog extends Component {

    Const CATE_SMS = 'sms';

    public static function error($text,$category ) {
        Yii::getLogger()->log($text,Logger::LEVEL_ERROR,$category);
    }

    public static function info($text,$category ) {
        Yii::getLogger()->log($text,Logger::LEVEL_INFO,$category);
    }

}