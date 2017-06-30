<?php
namespace oa\controllers;

use common\components\CommonFunc;
use ucenter\models\User;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public $user = false;       //用户对象
    public $navItems = [];      //导航栏
    public $layout = 'main';    //布局文件
    public $viewName = '';      //视图文件
    public $isMobile = false;   //表示是否为移动用户
    public $tabbar_on = 0;      //移动端导航栏激活标志
    public $except = [
        'site/error',
        'site/get-qiniu-uptoken'
    ];


    public function beforeAction($action){
        //$this->addUserHistory();  //记录用户访问日志
        if (!parent::beforeAction($action)) {
            return false;
        }else{
            CommonFunc::addHistory();

            /*error_log('['.date("Y-m-d H:i:s").'] url :'.Yii::$app->request->getAbsoluteUrl()."\n",3,'/var/www/error.log');

$s=5/0;

            if($this->id!='site' || $this->action->id!='error'){
                $code = Yii::$app->response->statusCode;
                error_log('1 c: '.$this->id.' a: '.$this->action->id.' status:'.$code."\n",3,'/var/www/error.log');
            }*/


            //var_dump(Yii::$app->response->statusCode);//Yii::$app->end();
            $this->checkLogin();  //检测用户登录 和 状态是否正常

            /*if(!Yii::$app->user->isGuest){
                $this->user = User::
            }*/

            //Yii::$app->setLayoutPath(Yii::$app->viewPath);  //修改读取布局文件的默认文件夹  原本为 views/layouts => views

            //$this->viewName = $this->action->id;  //一般视图名就等于动作名  site/login => login.php


            //$this->setNavItems(); //设置导航栏

            $this->isMobile = CommonFunc::isMobile(); //根据设备属性判断是否为移动用户

            //如果是移动设备
            if($this->isMobile){
                echo '手机版请使用微信';exit;
                $this->layout = 'mobile/main';
            }

            return true;
        }
    }

    //检测是否登陆
    public function checkLogin(){
        //除了上述访问路径外，需要用户登录，跳转至登录页面
        if (!in_array($this->route, $this->except)) {
            if(Yii::$app->user->isGuest) {
                $this->toLogin();
                return false;
            }else{
                $this->user = Yii::$app->user->identity;
                return $this->checkAuth();
            }
        }else{
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
        if(!Yii::$app->user->identity->isOaFrontend && !Yii::$app->user->identity->isOaFrontendAdmin  && !Yii::$app->user->identity->isSuperAdmin ){
            if($this->getRoute()=='site/no-auth'){
                return true;
            }else{
                return $this->redirect('/site/no-auth');
            }
        }else{
            return true;
        }
    }

}