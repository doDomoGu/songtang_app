<?php
namespace ucenter\controllers;

use ucenter\models\Area;
use ucenter\models\Business;
use ucenter\models\Department;
use ucenter\models\Position;
use ucenter\models\Structure;
use ucenter\models\User;
use ucenter\models\UserForm;
use Yii;
use yii\web\Response;

class UserController extends BaseController
{
    public function actionIndex(){
        $aid = Yii::$app->request->get('aid',false);
        $bid = Yii::$app->request->get('bid',false);
        $list = User::find()->where([])->groupBy('id')->all();


        $params['list'] = $list;
        $params['aArr'] = Area::getNameArr();
        $params['bArr'] = Business::getNameArr();
        $params['pArr'] = Position::getNameArr();
        $params['bArr2'] = Area::getRelationsArr($aid);

        $params['aid'] = $aid;
        $params['bid'] = $bid;

        return $this->render('index',$params);
    }

    public function actionAddAndEdit(){
        $model = new UserForm();
        $user = null;
        $updatePassword = true;
        $passwordTmp = null;
        $id = Yii::$app->request->get('id',0);
        if($id>0){
            $user = User::find()->where(['id'=>$id])->one();
            if($user){
                $this->view->title = '职员 - 编辑';
                $model->setScenario('update');
                $model->setAttributes($user->attributes);
                $model->password = '';
                //$user->setScenario('update');
            }else{
                Yii::$app->response->redirect('user')->send();
            }
        }else{
            $this->view->title = '职员 - 添加';
            $model->setScenario('create');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($user == null){
                $user = new User();
                //$user->setScenario('create');
            }
            if($model->getScenario()=='update'){
                if($model->password=='' || $model->password2==''){
                    $model->password = $user->password;
                    $updatePassword = false;
                }
            }elseif($model->getScenario()=='create'){
                $model->password = '123123';
                //$model->password = CommonFunc::generateCode(); //新增职员 自动创建随机密码
            }

            $user->setAttributes($model->attributes);

            if($updatePassword===true){
                $passwordTmp = $user->password;
                $user->password_true = $user->password;
                $user->password = md5($user->password);

            }

            if($user->save()){
                /*if($this->sendMail){
                    //发送邮件
                    if($model->getScenario()=='create'){
                        $mail = new MyMail();
                        $mail->to = $user->username;
                        $mail->subject = '【颂唐云】新职员注册成功';
                        $mail->htmlBody = '职员['.$user->name.'],您好：<br/>颂唐云网址为：http://yun.songtang.net 您的登录用户名为 '.$user->username.' 密码为 '.$passwordTmp;
                        $mail->send();
                    }elseif($model->getScenario()=='update'){
                        $mail = new MyMail();
                        $mail->to = $user->username;
                        if($updatePassword==true){
                            $mail->subject = '【颂唐云】职员信息变更(包括密码)';
                        }else{
                            $mail->subject = '【颂唐云】职员信息变更';
                        }
                        $mail->htmlBody = '职员['.$user->name.'],您好：<br/> 您的职员信息发生了更改。';
                        if($updatePassword==true){
                            $mail->htmlBody.=' <br/>您的登录密码变为 '.$passwordTmp;
                        }
                        $mail->send();
                    }
                }*/
                Yii::$app->response->redirect('/user')->send();
            }
        }

        $params['model'] = $model;
        return $this->render('add_and_edit',$params);
    }
}
