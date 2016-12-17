<?php
namespace oa\modules\admin\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{

    public $isMobile = false;   //表示是否为移动用户
    public $mobileNavItems = [];  //手机端导航栏 选项

    public function beforeAction($action){
        if (!parent::beforeAction($action)) {
            return false;
        }
        $this->checkLogin();
        /*if(!$this->checkLogin()){
            return false;
        }

        $this->isInRoom = $this->checkIsInRoom();

        $this->setNavItems();*/
//        $this->getMessageInfo();

        $this->isMobile = CommonFunc::isMobile(); //根据设备属性判断是否为移动用户

        if($this->isMobile) {  //如果是移动设备 调用另一个布局文件 启用导航栏
            Yii::$app->getModule('admin')->setLayoutPath(Yii::$app->getModule('admin')->viewPath . '/layouts_mobile');
            $this->setNavItems();
        }
        return true;
    }

    public function checkLogin(){
        //除“首页”和“登陆页面”以外的页面，需要进行登陆判断
        if(!in_array($this->route,array('admin/site/login','admin/site/captcha','admin/site/error','admin/site/logout'))){
            if($this->module->adminUser->isGuest){
                $session = Yii::$app->session;
                $session['referrer_url_admin'] = Yii::$app->request->getAbsoluteUrl();
                $this->redirect(Yii::$app->urlManager->createUrl($this->module->adminUser->loginUrl));
                Yii::$app->end();
            }
        }
        return true;
    }


    public function setNavItems(){
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
    }


}