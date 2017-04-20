<?php
namespace yun\controllers;

use Yii;

class TestController extends \yii\web\Controller
{
    public function actionIndex(){
        $this->layout = 'main_blank';
        $params = [];

        return $this->render('index',$params);
    }
}
