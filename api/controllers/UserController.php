<?php
namespace api\controllers;

use ucenter\models\User;
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

            if($this->handleRequestParams()==false){

                $result =   ['error_response'=>
                    [
                        'code'=>400,
                        'msg'=>$this->msg
                    ]
                ];
                return $this->afterAction($action,$result);

            }


            return true;
        }

        return false;
    }

    public function actionGet(){
        $id = $this->rParams['id'];
        $user = User::find()->where(['id'=>$id])->one();
        if($user){
            return ['user_get_response'=>
                [
                    'info'=>[
                        'username'=>$user->username,
                        'name' => $user->name,
                    ]
                ]
            ];
        }else{
            return ['error_response'=>
                [
                    'code'=>400,
                    'msg'=>'用户没找到'
                ]
            ];
        }

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
