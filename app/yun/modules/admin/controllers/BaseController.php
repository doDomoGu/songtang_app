<?php
namespace yun\modules\admin\controllers;

use common\components\CommonFunc;
use yun\modules\admin\components\AdminFunc;
use Yii;
use yii\web\Controller;
use ucenter\models\UserAppAuth;

class  BaseController extends Controller
{
    public $mobileNavItems = [];  //手机端导航栏 选项
    public $except = [];

    public function beforeAction($action){
        if (!parent::beforeAction($action)) {
            return false;
        }
        $this->except = [
            AdminFunc::adminUrl('default/error'),
        ];
        if(!CommonFunc::checkIpWhiteList()){
            return false;
        }
        $this->checkLogin();

        return true;
    }

    //检测是否登陆
    public function checkLogin(){
        //除了上述访问路径外，需要用户登录，跳转至登录页面
        if (!in_array('/'.$this->route, $this->except)) {
            if(Yii::$app->user->isGuest) {
                $this->toLogin();
                return false;
            }else{
                return $this->checkAuth();
            }
        }else{
            /*if(!Yii::$app->user->isGuest && '/'.$this->route == AdminFunc::adminUrl('default/no-auth')){
                return $this->redirect(AdminFunc::adminUrl('/'));
            }*/
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
    private function checkAuth(){
        if(!Yii::$app->user->identity->isYunBackendAdmin  && !Yii::$app->user->identity->isSuperAdmin){
            if('/'.$this->getRoute()==AdminFunc::adminUrl('default/no-auth')){
                return true;
            }else{
                return $this->redirect(AdminFunc::adminUrl('default/no-auth'));
            }
        }else{
            return true;
        }
    }
}