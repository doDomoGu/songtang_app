<?php
namespace ucenter\controllers;

use ucenter\models\User;
use ucenter\models\UserAppAuth;
use Yii;

class UserAppAuthController extends BaseController
{
    public function actionIndex(){
        $appArr = UserAppAuth::getAppArr();
        $list = [];
        $users = UserAppAuth::find()->where([])->groupBy('user_id')->all();
        foreach($users as $u){
            $tmp = [];
            $tmp['user'] = $u->user;
            $auths = UserAppAuth::find()->where(['user_id'=>$u->user_id])->all();
            foreach($auths as $auth){
                if(in_array($auth->app,$appArr))
                    $tmp['auth'][] = $auth->app;
            }
            $list[] = $tmp;
        }

        $params['list'] = $list;

        return $this->render('index',$params);
    }

    public function actionChange(){
        $user_id = Yii::$app->request->get('user_id',false);
        $app = Yii::$app->request->get('app',false);
        $act = Yii::$app->request->get('act',false);
        if(in_array($act,['add','del','new'])){
            if(in_array($app,UserAppAuth::getAppArr())){
                $user = User::find()->where(['id'=>$user_id])->one();
                if($user){
                    $existAuth = UserAppAuth::hasAuth($user_id,$app);
                    if($act=='add' || $act=='new'){
                        if(!$existAuth){
                            $n = new UserAppAuth();
                            $n->user_id = $user_id;
                            $n->app = $app;
                            $n->is_enable = 1;
                            $n->save();
                            if($act == 'new'){
                                echo json_encode(['success'=>true]);
                            }else{
                                $this->redirect('/user-app-auth');
                            }
                        }else{
                            echo 'has exist';exit;
                        }
                    }else{
                        if($existAuth){
                            UserAppAuth::deleteAll(['user_id'=>$user_id,'app'=>$app]);
                            $this->redirect('/user-app-auth');
                        }else{
                            echo 'has deleted';exit;
                        }
                    }
                }else{
                    echo 'wrong user';exit;
                }
            }else{
                echo 'wrong app name';exit;
            }
        }else{
            echo 'wrong act';exit;
        }
    }
}
