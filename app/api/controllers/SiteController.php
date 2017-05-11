<?php
namespace api\controllers;

use Yii;
use yii\base\Controller;

class SiteController extends Controller
{


    public function actionIndex()
    {
        $errFile = __DIR__.'/../runtime/logs/api.log';
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        $result= ['get'=>$get,'post'=>$post];

        error_log(date('Y-m-d H:i:s').'  '.json_encode($result)."\n",3,$errFile);

        $response=Yii::$app->response;
        $response->format= \yii\web\Response::FORMAT_JSON;
        $response->data = $result;

        $response->send();

    }

    public function actionError(){
        $result = ['error_response'=>
            [
                'code'=>404,
                'msg'=>'非法请求'
            ]
        ];
        $response=Yii::$app->response;
        $response->format= \yii\web\Response::FORMAT_JSON;
        $response->data = $result;

        $response->send();
    }
}
