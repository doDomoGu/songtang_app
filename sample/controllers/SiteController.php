<?php
namespace oa\controllers;

use backend\models\Area;
use frontend\models\UserIdentity;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
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
        $area = Area::find()->all();
        foreach($area as $a){
            var_dump($a->attributes);echo '<br/>';
        }
        exit;
        return $this->render('index');
    }
}
