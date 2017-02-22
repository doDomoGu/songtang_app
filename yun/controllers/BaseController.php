<?php
namespace yun\controllers;

use ucenter\models\UserAppAuth;
use yun\components\PermissionFunc;
use yun\models\MessageUser;
use yun\models\Position;
use yun\models\PositionDirPermission;
use ucenter\models\User;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public $titleSuffix;
    public $user;
    public $navbarView = 'navbar';
    public $position;
    //public $message = [];
    //public $messageNum = 0;
    public $previewTypeArr = [2,3,4,5,6];
    public $except = [];  //未登录也可以访问的页面 排除

    public function beforeAction($action){
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->titleSuffix = '_'.yii::$app->name;
        $this->except = [
            'site/index',
            'site/error',
            'site/get-qiniu-uptoken',
            'version/index'
        ];
        if(!$this->checkLogin()){
            return false;
        }

        //$this->getMessageInfo();

        return true;
    }

    //检测是否登陆
    public function checkLogin(){
        //除了上述访问路径外，需要用户登录，跳转至登录页面
        if (!in_array($this->route, $this->except)) {
            if(Yii::$app->user->isGuest) {
                $this->toLogin();
                return false;
            }else{
                $this->checkAuth();
                return true;
            }
        }else{
            $this->checkAuth();
            return true;
        }
    }

    //跳转至登录页面
    private function toLogin(){
        //session记录当前页面的url  登录后返回
        $session = Yii::$app->session;
        $session['referrer_url_user'] = Yii::$app->request->getAbsoluteUrl();

        $this->redirect(Yii::$app->params['loginUrl']);
        Yii::$app->end();
    }

    //检查是否有使用这个app权限
    private function checkAuth($redirect=false){
        $hasAuth = UserAppAuth::hasAuth(Yii::$app->user->id,'yun-frontend');
        if(!$hasAuth){
            if($redirect){
                if($this->getRoute()=='site/no-auth'){
                    return true;
                }else{
                    return $this->redirect('site/no-auth');
                }
            }
        }else{
            return true;
        }
    }


    //获取登录用户的消息通知提醒
    public function getMessageInfo(){
        if(!Yii::$app->user->isGuest){
            $this->message = MessageUser::find()->where(['send_to_id'=>yii::$app->user->id,'read_status'=>0])->all();
            if(!empty($this->message)){
                $this->messageNum = count($this->message);
            }
        }
    }
}