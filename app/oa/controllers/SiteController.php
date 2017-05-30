<?php
namespace oa\controllers;

use common\models\Sms;
use ucenter\models\Area;
use Yii;
use yii\debug\models\search\Log;
use yii\log\Logger;
use yii\web\Controller;

use yun\components\QiniuUpload;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        /*$s = Yii::$app->request->get('send-test',false);
        if($s){
            $mobile = '18017865582';
            $scenario = 'new_msg';
            $param = ['title'=>'test-test'];
            Sms::insertWithTemplate($mobile,$scenario,$param);
        }*/
        if($this->isMobile){
            $this->tabbar_on = 1;
            return $this->render('mobile/index');
        }else
            return $this->render('index');
    }

    public function actionApply(){
        $this->tabbar_on = 2;
        return $this->render('mobile/apply');
    }

    /*public function actionWork(){
        $this->tabbar_on = 3;
        return $this->render('work');
    }*/

    public function actionMe(){
        $this->tabbar_on = 4;
        return $this->render('mobile/me');
    }

    public function actionNoAuth(){
        return $this->render('no_auth');
    }

    public function actionGetQiniuUptoken(){
        $up=new QiniuUpload(yii::$app->params['qiniu-oa-bucket']);
        $saveKey = yii::$app->request->get('saveKey','');
        $upToken=$up->createtoken($saveKey);
        echo json_encode(['uptoken'=>$upToken]);exit;
    }

}
