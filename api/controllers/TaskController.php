<?php
namespace api\controllers;

use Yii;

class TaskController extends BaseController
{
    public function actionIndex(){
        echo 'task index';
       // Yii::$app->end();
    }

    public function helpIndex(){
        echo 'Help : task index';
    }
}
