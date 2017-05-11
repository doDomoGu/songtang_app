<?php
namespace login\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public $user = false;       //用户对象

    public function beforeAction($action){
        //$this->addUserHistory();  //记录用户访问日志
        if (!parent::beforeAction($action)) {
            return false;
        }else{
            $this->checkLogin();  //检测用户登录 和 状态是否正常
            return true;
        }
    }

    public function checkLogin(){
        if(Yii::$app->user->isGuest) {
            //用户未登录
            $except = [
                'site/login',
                'site/captcha',
                'site/error'
            ];
            //除了上述访问路径外，需要用户登录，跳转至登录页面
            if (!in_array($this->route, $except)) {
                $this->toLogin();
            }
        }
        return true;
    }

    //跳转至登录页面
    private function toLogin(){
        //session记录当前页面的url  登录后返回
        $session = Yii::$app->session;
        $session['referrer_url_user'] = Yii::$app->request->getAbsoluteUrl();

        $this->redirect(Yii::$app->urlManager->createUrl(Yii::$app->user->loginUrl));
        Yii::$app->end();
    }

}