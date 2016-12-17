<?php

namespace oa\modules\admin\controllers;

use oa\models\OaFlow;
use oa\models\OaTask;
use oa\modules\admin\components\AdminFunc;
use ucenter\models\Area;
use ucenter\models\Business;
use ucenter\models\Position;
use ucenter\models\User;
use Yii;
use yii\web\Response;
/**
 *  任务流程管理
 *  task flow
 */
class TaskController extends BaseController
{
    public function actionIndex()
    {
        $aid = Yii::$app->request->get('aid',false);
        $bid = Yii::$app->request->get('bid',false);
        $list = OaTask::find()->all();


        $params['list'] = $list;
        $params['aArr'] = Area::getNameArr();
        $params['bArr'] = Business::getNameArr();
        $params['pArr'] = Position::getNameArr();
        $params['bArr2'] = Area::getRelationsArr($aid);

        $params['aid'] = $aid;
        $params['bid'] = $bid;
        return $this->render('index',$params);
    }

    public function actionCreate(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $title = trim(Yii::$app->request->post('title',false));
            $area_id = intval(Yii::$app->request->post('area_id',0));
            //AREA BUSINESS DEPARTMENT  TODO
            if($title==''){
                $errormsg = '名称或别名不能为空！';
            }else{
                $exist = OaTask::find()->where(['title'=>$title])->one();
                if($exist){
                    $errormsg = '标题已存在!';
                }else{
                    $task = new OaTask();
                    $task->title = $title;
                    $task->area_id = $area_id;
                    $task->business_id = $area_id;
                    $task->department_id = $area_id;
                    $task->ord = 0;
                    $task->status = 1;
                    if($task->save()){
                        Yii::$app->getSession()->setFlash('success','新增任务【'.$task->title.'】成功！');
                        $result = true;
                    }else{
                        $errormsg = '保存失败，刷新页面重试!';
                    }
                }
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

    public function actionFlow(){
        $tid = Yii::$app->request->get('tid',false);
        $task = OaTask::find()->where(['id'=>$tid])->one();
        if($task){
            $flow = OaFlow::find()->where(['task_id'=>$tid])->orderBy('step asc')->all();
            $params['list'] = $flow;
            $params['task'] = $task;
            return $this->render('flow',$params);
        }else{
            Yii::$app->getSession()->setFlash('error','流程设置对应的任务id不存在!');
            return $this->redirect(AdminFunc::adminUrl('task'));
        }
    }

    public function actionFlowCreate(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $title = trim(Yii::$app->request->post('title',false));
            $type = intval(Yii::$app->request->post('type',0));
            $user_id = intval(Yii::$app->request->post('user_id',0));
            $tid = intval(Yii::$app->request->post('tid',0));
            if($title==''){
                $errormsg = '名称或别名不能为空！';
            }else{
                $exist = OaTask::find()->where(['id'=>$tid])->one();
                if(!$exist){
                    $errormsg = '对应的任务ID不存在！';
                }else{
                    $existUser = User::find()->where(['id'=>$user_id])->one();
                    if(!$existUser){
                        $errormsg = '所选职员ID不存在！';
                    }else{
                        $last = OaFlow::find()->where(['task_id'=>$tid])->orderBy('step desc')->one();
                        $flow = new OaFlow();
                        $flow->title = $title;
                        $flow->task_id = $tid;
                        $flow->user_id = $user_id;
                        $flow->type = $type;
                        $flow->step = isset($last)?$last->step+1:1;
                        $flow->status = 1;
                        if($flow->save()){
                            Yii::$app->getSession()->setFlash('success','新增流程【'.$flow->title.'】成功！');
                            $result = true;
                        }else{
                            $errormsg = '保存失败，刷新页面重试!';
                        }
                    }
                }
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }
}
