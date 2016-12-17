<?php
namespace oa\modules\admin\controllers;

use oa\modules\admin\components\AdminFunc;
use Yii;
use yii\web\Controller;
use ucenter\models\UserAppAuth;

class BaseController extends Controller
{
    public $mobileNavItems = [];  //手机端导航栏 选项
    public $except = [];
    public $hasAuth = false;

    public function beforeAction($action){
        if (!parent::beforeAction($action)) {
            return false;
        }
        $this->except = [
            AdminFunc::adminUrl('default/error'),
            AdminFunc::adminUrl('default/no-auth')
        ];
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
            if(!Yii::$app->user->isGuest && '/'.$this->route == AdminFunc::adminUrl('default/no-auth')){
                return $this->redirect(AdminFunc::adminUrl('/'));
            }
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
        $authExist = UserAppAuth::find()->where(['app'=>'oa-admin','uid'=>Yii::$app->user->id,'is_enable'=>1])->one();
        if(!$authExist){
            if('/'.$this->getRoute()==AdminFunc::adminUrl('default/no-auth')){
                return true;
            }else{
                return $this->redirect(AdminFunc::adminUrl('default/no-auth'));
            }
        }else{
            $this->hasAuth = true;
            return true;
        }
    }


    /*public function setNavItems(){
        $items[] = ['label' => '仪表盘', 'url' => [AdminFunc::adminUrl('')]];
        $items[] = ['label' => '玩家', 'url' => [AdminFunc::adminUrl('user')]];
        $items[] = ['label' => '游戏', 'url' => [AdminFunc::adminUrl('game')]];
        $items[] = ['label' => '网站管理', 'items'=>[
            ['label'=>'手机短信','url'=>[AdminFunc::adminUrl('manage/sms')]],
            ['label'=>'验证码','url'=>[AdminFunc::adminUrl('manage/verify-code')]],
            ['label'=>'参数设置','url'=>[AdminFunc::adminUrl('manage/global-config')]],
            ['label'=>'用户操作记录','url'=>[AdminFunc::adminUrl('manage/user-history')]]
            ]
        ];

        $items[] = ['label' => '退出', 'url' => [AdminFunc::adminUrl('site/logout')]];
        $this->mobileNavItems = $items;
    }*/


}