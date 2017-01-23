<?php
namespace api\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public $rParams = [];  //从客户端 请求的参数
    public $allowArr = [];   //允许提交的参数   第一层下标为action名  第二层下标为参数键名 值为规定的数据格式
    public $requireArr = []; //必须提交的参数   第一层下标为action名  值为参数键名
    public $msg;

    //
    public function beforeAction($action){
        if (parent::beforeAction($action)) {
            //TODO  访问日志

            //TODO  其他逻辑代码
            //echo 'base controller';
            return true;
        }

        return false;



        //return true;

        //$this->addUserHistory();  //记录用户访问日志
        /*if (!parent::beforeAction($action)) {
            return false;
        }else{
            $this->checkLogin();  //检测用户登录 和 状态是否正常
            return true;
        }*/
    }


    /*
     * handleRequestParams
     * 功能：处理客户端请求过来的参数， 先验证 (必填参数，参数格式，错误的参数） 再将参数整理赋值给$requestParams
     */
    public function handleRequestParams(){
        $act = $this->action->id;
        $allowArr = isset($this->allowArr[$act])?$this->allowArr[$act]:[];   //允许的参数
        $requireArr = isset($this->requireArr[$act])?$this->requireArr[$act]:[];  //必填的参数
        //$post = Yii::$app->request->post();


        $get = Yii::$app->request->get();  //请求的参数
        $post = Yii::$app->request->post();  //请求的参数
        $request = array_merge($get,$post);



        $allowKeys = array_keys($allowArr);
        $requireKeys = $requireArr;
        $requestKeys = array_keys($request);

        //验证参数是否设置正确 不矛盾
        if(count(array_intersect($allowKeys,$requireKeys)) != count($requireKeys)){
            $this->msg = '方法参数设置错误';
            return false;
        }else{
            //验证没有提交多余的参数
            if(count(array_intersect($allowKeys,$requestKeys)) != count($requestKeys)){
                $this->msg = '请求参数错误 （多余参数）';
                return false;
            }else{
                if(count(array_intersect($requestKeys,$requireKeys)) != count($requireKeys)){
                    $this->msg = '请求参数错误 （必填参数没有）';
                    return false;
                }else{
                    //TODO 格式验证
                    $this->rParams = $request;


                    return true;
                }
            }
        }

    }

    public function afterAction($action,$result){
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data = $result;
    }

}
