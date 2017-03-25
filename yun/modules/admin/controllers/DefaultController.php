<?php

namespace yun\modules\admin\controllers;

use Yii;

class DefaultController extends BaseController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionNoAuth(){
        return $this->render('no_auth');
    }


    public function actionInstall(){
       /* $n = new TaskCategory();
        $n->install();*/
    }

    public function actionClearCache(){

    }
}
