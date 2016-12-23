<?php
namespace yun\controllers;

use common\models\UserAppAuth;
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
    public $navbarView = 'navbar2';
    public $position;
    public $message = [];
    public $messageNum = 0;
    public $previewTypeArr = [2,3,4,5,6];
    //public $layout = 'main';
    public function beforeAction($action){
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->titleSuffix = '_'.yii::$app->id;
        if(!Yii::$app->user->isGuest){
            $this->user = User::find()->where(['id'=>yii::$app->user->id])->one();
            if(!$this->user->status==1){
                Yii::$app->user->logout();
                return $this->goHome();
            }

            //$this->position = Position::find()->where(['id'=>$this->user->position_id])->one();
        }


        if(!$this->checkLogin()){
            return false;
        }

        //$this->getMessageInfo();

        return true;
    }

    //检测是否登陆
    public function checkLogin(){
        //除“首页”和“登陆页面”以外的页面，需要进行登陆判断
        if(!in_array($this->route,array('site/index','site/login','help/index','version/index','stat/position','test/position'))){
            if(Yii::$app->user->isGuest){
                $this->redirect(Yii::$app->urlManager->createUrl(Yii::$app->user->loginUrl));
                return false;
            }
        }

        return true;
    }

    //检查是否有使用这个app权限
    private function checkAuth(){
        $authExist = UserAppAuth::find()->where(['app'=>'yun-admin','user_id'=>Yii::$app->user->id,'is_enable'=>1])->one();
        if(!$authExist){
            if($this->getRoute()=='site/no-auth'){
                return true;
            }else{
                return $this->redirect('site/no-auth');
            }
        }else{
            $this->hasAuth = true;
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