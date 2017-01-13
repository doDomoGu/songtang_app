<?php
namespace api\controllers;

use Yii;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionIndex()
    {
        echo 'user';
        Yii::$app->end();
    }
}
