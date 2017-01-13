<?php
namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public function beforeAction($action){
        //TODO  访问日志

        if (!parent::beforeAction($action)) {
            return false;
        }else{
            return true;
        }



        //return true;

        //$this->addUserHistory();  //记录用户访问日志
        /*if (!parent::beforeAction($action)) {
            return false;
        }else{
            $this->checkLogin();  //检测用户登录 和 状态是否正常
            return true;
        }*/
    }

    public function afterAction($action,$result){
        if (!parent::beforeAction($action)) {
            return false;
        }else{
            $response=Yii::$app->response;
            $response->format=Response::FORMAT_JSON;
            $response->data = $result;
        }
    }
}
