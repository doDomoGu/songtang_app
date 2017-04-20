<?php
namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public $request = [];  //从客户端 请求的参数
    public $format = [];  //请求参数格式   第一层下标为action名  第二层下标为参数键名 值为 type（数据类型） required （是否必填） 和 explian （说明） 组成的数组
    public $msg;

    public function beforeAction($action){
        if (parent::beforeAction($action)) {
            header("Access-Control-Allow-Origin:*");
            //TODO  访问日志

            //TODO  其他逻辑代码

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
     * 功能：处理客户端请求过来的参数， 先验证 (必填参数，参数格式，错误的参数） 再将参数整理赋值给$request
     */
    public function handleRequestParams(){
        $act = $this->action->id;
        if(isset($this->format[$act])){
            //参数格式
            $format = $this->format[$act];
            $param = $format['param'];

            //请求的参数
            $get = Yii::$app->request->get();
            $post = Yii::$app->request->post();
            $request = array_merge($get,$post);
            $requestKeys = array_keys($request);


            //允许的参数
            $allowKeys = array_keys($param);

            //必填的参数
            $requireKeys = [];
            foreach($param as $k => $v){
                if(isset($v['required']) && $v['required']==true){
                    $requireKeys[] = $k;
                }
            }

            //验证有没有提交多余的参数
            if(!empty($requestKeys) && count(array_intersect($allowKeys,$requestKeys)) != count($requestKeys)){
                $this->msg = '请求参数错误 （提交了多余的参数）';
                return false;
            }else{
                //验证有没有提交必填的参数
                if(!empty($requireKeys) && count(array_intersect($requestKeys,$requireKeys)) != count($requireKeys)){
                    $this->msg = '请求参数错误 （有必填参数没有提交）';
                    return false;
                }else{
                    //TODO 格式验证
                    $this->request = $request;

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
