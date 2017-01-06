<?php
namespace oa\controllers;

use common\models\Sms;
use ucenter\models\Area;
use Yii;
use yii\debug\models\search\Log;
use yii\log\Logger;
use yii\web\Controller;

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
        return $this->render('index');
    }

}
