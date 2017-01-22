<?php
namespace api\controllers;

use Yii;

class UserController extends BaseController
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->allowArr = [
                'get'=>['id'=>'int'],
                'change'=>['id'=>'int','name'=>'str']
            ];
            $this->requireArr = [
                'get'=>['id'],
                'change'=>['id','name'],
                'add'=>['name']
            ];

            $this->handleRequestParams();
            return true;
        }

        return false;
    }

    public function actionGet(){
        //$user_id = Yii::$app->request->post('')
    }

    public function helpGet(){
        echo 'Help : user get';//exit;
        //Yii::$app->end();
    }

    public function actionChange(){
        echo 'user change';
    }
    
    public function helpChange(){
        echo 'Help : user change';
    }

    public function actionAdd(){
        echo 'user add';
    }

    public function helpAdd(){
        echo 'Help : user add';
    }
}
