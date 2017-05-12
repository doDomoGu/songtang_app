<?php
namespace login\controllers;

use login\models\UserIdentity;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use login\models\LoginForm;
use login\models\PasswordResetRequestForm;
use login\models\ResetPasswordForm;
use login\models\SignupForm;
use login\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends BaseController
{

    public $defaultAction = 'login';
    public $enableCsrfValidation = false;

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
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->layout = false;
            return $this->render('login_success');
            //return $this->goHome();
        }else{
            $this->layout = false;
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                $session = Yii::$app->session;
                if($session['referrer_url_user']){
                    return $this->redirect($session['referrer_url_user']);
                }else{
                    return $this->goBack();
                }
            } else {
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    public function actionRee(){

    }

}
