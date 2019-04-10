<?php
namespace ucenter\controllers;

use common\components\CommonFunc;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public $user = false;       //用户对象
    public $navItems = [];      //导航栏
    public $layout = 'main';    //布局文件
    public $viewName = '';      //视图文件
    public $isMobile = false;   //表示是否为移动用户
    public $hasAuth = false;    //表示是否有权限使用
    public $routeExcepted = [
        'site/login',
        //'site/captcha',
        'site/error',
        //'site/add-history'

//        'site/send-test',
//        'site/test',
//        'site/install'
    ];


    public function beforeAction($action){
        //$this->addUserHistory();  //记录用户访问日志
        if (!parent::beforeAction($action)) {
            return false;
        }else{
            /*error_log('['.date("Y-m-d H:i:s").'] url :'.Yii::$app->request->getAbsoluteUrl()."\n",3,'/var/www/error.log');

$s=5/0;

            if($this->id!='site' || $this->action->id!='error'){
                $code = Yii::$app->response->statusCode;
                error_log('1 c: '.$this->id.' a: '.$this->action->id.' status:'.$code."\n",3,'/var/www/error.log');
            }*/
CommonFunc::addHistory();
            //var_dump(Yii::$app->response->statusCode);//Yii::$app->end();
            if(!CommonFunc::checkIpWhiteList()){
                return false;
            }

            $this->checkLogin();  //检测用户是否登录和状态是否正常

           // $this->checkAppAuth(); //检测用户是否有当前APP的权限

 //var_dump($s);exit;
            //Yii::$app->setLayoutPath(Yii::$app->viewPath);  //修改读取布局文件的默认文件夹  原本为 views/layouts => views

            //$this->viewName = $this->action->id;  //一般视图名就等于动作名  site/login => login.php


            //$this->setNavItems(); //设置导航栏

            //$this->isMobile = CommonFunc::isMobile(); //根据设备属性判断是否为移动用户

            //如果是移动设备
            /*if($this->isMobile){
                $this->layout = 'main_web';
            }*/

            return true;
        }
    }

    /**
     * 检测用户是否登录
     *
     * @return bool
     */
    private function checkLogin(){
        //除了上述访问路径外，需要用户登录，跳转至登录页面
        if (!in_array($this->route, $this->routeExcepted)) {
            if(Yii::$app->user->isGuest) {
                $this->toLogin();
                return false;
            }else{
                $this->user = Yii::$app->user->identity;
                return $this->checkAuth();

                //return true;
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
        if(!Yii::$app->user->identity->isUcenterAdmin && !Yii::$app->user->identity->isSuperAdmin){
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