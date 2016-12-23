<?php

namespace yun\modules\admin;

use Yii;


class Module extends \yii\base\Module
{
    public $controllerNamespace = 'yun\modules\admin\controllers';
    public $layout = 'main';


    public function init()
    {
        parent::init();
        Yii::$app->errorHandler->errorAction = 'admin/default/error';
    }
}
