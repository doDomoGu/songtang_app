<?php
namespace api\modules\v1\controllers;

use ucenter\models\UserWxOpenid;
use ucenter\models\UserWxSession;
use ucenter\models\User;
use yii\rest\ActiveController;
use yii\web\Response;
use Yii;

class SiteController extends ActiveController
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

        $request = Yii::$app->request;
        echo "<pre>";
        var_dump($request);exit;

        return ['ss2222s','222'];
        if(Yii::$app->request->get('key_pp')!=''){
            $key = Yii::$app->request->get('key_pp');
            $r = WxUserSession::find()->where(['key'=>$key])->one();
            if($r){
                $value = json_decode($r->value,true);
            }else{
                $value = false;
            }
            var_dump($key);echo '<br/>';
            var_dump($value);exit;
        }


        $result = 'failure';
        $key = '';
        $app_id = 'wxfeb4bdcd2e97f17b';
        $app_secret = '558f4d98ab4a03e9ccb5e20270806436';

        $str = date('Y-m-d H:i:s').'  ';
        $str .= json_encode($_POST);
        $str .= "\n";
        error_log($str,3,'/Users/doDomoGu/dev0/api.log');

        $code = isset($_POST['code']) ? $_POST['code']:false;
        //$encryptedData = isset($_POST['encryptedData']) ? $_POST['encryptedData']:false;
        //$iv = isset($_POST['iv']) ? $_POST['iv']:false;
        if($code){
            $result = file_get_contents('https://api.weixin.qq.com/sns/jscode2session?appid='.$app_id.'&secret='.$app_secret.'&js_code='.$code.'&grant_type=authorization_code');
            error_log(date('Y-m-d H:i:s').'  '.$result."\n",3,'/Users/doDomoGu/dev0/api.log');

            $r = json_decode($result);
            if($r!=false){
                //$wx_session_key = $r->session_key;  //微信用户的session_key
                $openid = $r->openid;  //oXsYJ0UVLNP6lrKk52DcfYg2DVOk

                $sessionKey = md5(time()+rand(0,100));

                $bindUser = UserWxOpenid::find()->where(['appid'=>$app_id,'openid'=>$openid])->one();

                $key = 'wx_login_'.$sessionKey;

                if($bindUser){
                    $user_id = $bindUser->user_id;
                    $user = User::find()->where(['id'=>$user_id])->one();
                    if($user){
                        $msg = 'get user';
                    }else{
                        $msg = 'wrong user_id';
                    }
                }else{
                    $user_id = 0;
                    $msg = 'no user';
                }

                $value = json_encode(['openid'=>$openid,'user_id'=>$user_id]);

                $newSession = new WxUserSession();
                $newSession->key = $key;
                $newSession->value = $value;
                $newSession->save();

                error_log(date('Y-m-d H:i:s').'  '.$key.' => '.$value."\n",3,'/Users/doDomoGu/dev0/api.log');
                $result = 'success';
            }else{
                $msg = 'not found openid';
            }
        }else{
            $msg = 'no code';
        }


        return ['result'=>$result,'msg'=>$msg,'session_key'=>$key];
        /*"https://api.weixin.qq.com/sns/jscode2session?appid="+that.globalData.appid+"&secret="+that.globalData.appsecret+"&js_code="+res.code+"&grant_type=authorization_code"*/

    }
}
