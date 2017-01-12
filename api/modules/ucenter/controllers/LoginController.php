<?php
namespace api\modules\ucenter\controllers;
include_once '../../common/extensions/qiniu-crypt/wxBizDataCrypt.php';

use ucenter\models\User;
use yii\rest\ActiveController;
use yii\web\Response;
use WXBizDataCrypt;

class LoginController extends ActiveController
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
    public function actions()
    {
        $actions = parent::actions();

        // 注销系统自带的实现方法
        unset($actions['index']);

        //unset($actions['create']);
        //unset($actions['update']);
        //unset($actions['delete']);

        return $actions;
    }

    public function verbs(){
        return [
            'index' => ['POST', 'GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex()
    {
        $app_id = 'wxfeb4bdcd2e97f17b';
        $app_secret = '558f4d98ab4a03e9ccb5e20270806436';

        $str = date('Y-m-d H:i:s').'  ';
        $str .= json_encode($_POST);
        $str .= "\n";
        error_log($str,3,'/Users/doDomoGu/dev0/api.log');

        $code = isset($_POST['code']) ? $_POST['code']:false;
        $encryptedData = isset($_POST['encryptedData']) ? $_POST['encryptedData']:false;
        $iv = isset($_POST['iv']) ? $_POST['iv']:false;
        if($code){
            $result = file_get_contents('https://api.weixin.qq.com/sns/jscode2session?appid='.$app_id.'&secret='.$app_secret.'&js_code='.$code.'&grant_type=authorization_code');
            error_log(date('Y-m-d H:i:s').'  '.$result."\n",3,'/Users/doDomoGu/dev0/api.log');

            $r = json_decode($result);


            /*$pc = new WXBizDataCrypt($app_id, $r->session_key);
            $errCode = $pc->decryptData($encryptedData, $iv, $data );

            if ($errCode == 0) {
                $message = $data;
            } else {
                $message = $errCode;
            }*/
        }else{
            $message = 'no code';
        }


        return ['result'=>'success','msg'=>$message];

        /*"https://api.weixin.qq.com/sns/jscode2session?appid="+that.globalData.appid+"&secret="+that.globalData.appsecret+"&js_code="+res.code+"&grant_type=authorization_code"*/

    }
}
