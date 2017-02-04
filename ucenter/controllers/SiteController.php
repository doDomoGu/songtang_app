<?php

namespace ucenter\controllers;

use ucenter\models\Area;
use ucenter\models\Business;
use ucenter\models\Department;
use ucenter\models\Position;
use ucenter\models\Structure;
use ucenter\models\User;
use ucenter\models\UserAppAuth;
use Yii;
//use ucenter\models\LoginForm;
use yii\web\Response;

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

    public function actionIndex()
    {
        return $this->render('index');
    }

    /*public function actionError(){


        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
            $exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = $this->defaultMessage ?: Yii::t('yii', 'An internal server error occurred.');
        }
        error_log('error c: '.$this->id.' a: '.$this->action->id.' status:'.$code."\n",3,'/var/www/error.log');
        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        } else {
            return $this->render('error', [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ]);
        }
    }*/

    /*public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }*/

    public function actionTest(){
        $list = User::find()->all();
foreach($list as $l){
    var_dump($l->id,$l->position_id,$l->position->name);
    echo '<br/><br/>';
}
        Yii::$app->end();
        var_dump($list);exit;
    }

    public function actionInstall(){
        $n = new Area();
        $n->install();

        $n = new Business();
        $n->install();

        $n = new Department();
        $n->install();

//        $n = new Structure();
//        $n->install();

        $n = new Position();
        $n->install();

        $n = new User();
        $n->install();

        $n = new UserAppAuth();
        $n->install();
    }

    public function actionGetUser(){
        $result = false;
        $data = false;
        $uid = Yii::$app->request->get('uid',false);
        $username = Yii::$app->request->get('username',false);
        if($username!=''){
            $user = User::find()->where(['username'=>$username])->one();
        }else{
            $user = User::find()->where(['id'=>$uid])->one();
        }
        if($user){
            $data = $user;
            $result = true;
        }

        $response = Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'data'=>$data];
    }

    public function actionNoAuth(){
        if($this->hasAuth){
            return $this->redirect('/');
        }else{
            $this->layout = false;
            return $this->render('no-auth');
        }
    }
}
