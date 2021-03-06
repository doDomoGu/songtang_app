<?php

namespace api\modules\ucenter\controllers;

use ucenter\models\User;
use yii\rest\ActiveController;
use yii\web\Response;

class UserController extends ActiveController
{
    public function init(){
        $this->modelClass = User::className();
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];
        return $behaviors;
    }
    /*public function actions()
    {
        $actions = parent::actions();

        // 注销系统自带的实现方法
        unset($actions['index']);

        //unset($actions['create']);
        //unset($actions['update']);
        //unset($actions['delete']);

        return $actions;
    }

    public function actionIndex()
    {
        return $this->render('index');
    }*/
}
