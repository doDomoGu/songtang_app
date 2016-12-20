<?php
namespace oa\controllers;

use oa\components\Func;
use oa\models\OaApply;
use oa\models\OaApplyCreateForm;
use oa\models\OaApplyDoForm;
use oa\models\OaApplyRecord;
use oa\models\OaFlow;
use oa\models\OaTask;
use oa\models\OaTaskApplyUser;
use yii\web\Response;
use Yii;

class ApplyController extends BaseController
{
    public function actionCreate(){
        $model = new OaApplyCreateForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $new = new OaApply();
            $new->attributes = $model->attributes;
            $new->user_id = Yii::$app->user->id;
            $new->flow_step = 1;
            $new->add_time = date('Y-m-d H:i:s');
            $new->edit_time = date('Y-m-d H:i:s');
            $new->status = 1;
            if($new->save()){
                //Yii::$app->session->setFlash()
                return $this->redirect('/');
            }
        }
        $params['model'] = $model;
        $params['tasks'] = Func::getTasksByUid(Yii::$app->user->id);
        return $this->render('create',$params);
    }

    /*
     * get-task-preview 获取申请任务的预览信息
     * 返回 html
     */
    public function actionGetTaskPreview(){
        $errormsg = '';
        $result = false;
        $html = '';
        if(Yii::$app->request->isAjax){
            $task_id = trim(Yii::$app->request->post('task_id',false));
            $task = OaTask::find()->where(['id'=>$task_id])->one();
            if($task){
                $flows = OaFlow::find()->where(['task_id'=>$task_id])->all();
                if(!empty($flows)){
                    $html.='<h3>申请表预览：</h3>';
                    foreach($flows as $f){
                        $htmlOne = '<li>';
                        $htmlOne.= '<div class="task-preview-step">步骤'.$f->step.'</div>';
                        $htmlOne.= '<div>标题：'.$f->title.'</div>';
                        $htmlOne.= '<div>类型：'.$f->typeName.'</div>';
                        $htmlOne.= '<div>操作人：'.$f->user->name.'</div>';
                        $htmlOne.= '</li>';
                        $html .= $htmlOne;
                    }
                    $result = true;
                }else{
                    $errormsg = '申请任务表没有设置流程！';
                }
            }else{
                $errormsg = '申请任务表不存在！';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'html'=>$html];
    }


    /*
     * get-record 获取申请表详情
     * 返回 html
     */
    public function actionGetRecord(){
        $errormsg = '';
        $result = false;
        $html = '';
        if(Yii::$app->request->isAjax){
            $id = trim(Yii::$app->request->post('id',false));
            $apply = OaApply::find()->where(['id'=>$id])->one();
            if($apply){
                $html .= '<li><div>发起申请</div></li>';
                $result = true;
                $records = OaApplyRecord::find()->where(['apply_id'=>$id])->all();
                if(!empty($records)){
                    foreach($records as $r){
                        $htmlOne = '<li>';
                        $htmlOne.= '<div class="task-preview-step">步骤'.$r->flow->step.'</div>';
                        $htmlOne.= '<div>标题：'.$r->flow->title.'</div>';
                        $htmlOne.= '<div>类型：'.$r->flow->typeName.'</div>';
                        $htmlOne.= '<div>操作人：'.$r->flow->user->name.'</div>';
                        $htmlOne.= '</li>';
                        $html .= $htmlOne;
                    }
                }
            }else{
                $errormsg = '申请表不存在！';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'html'=>$html];
    }


    //我的申请
    public function actionMy(){
        $list = OaApply::find()->where(['user_id'=>Yii::$app->user->id])->orderBy('add_time desc')->all();

        $params['list'] = $list;
        return $this->render('my',$params);
    }

    //待办事项
    public function actionTodo(){
        //检索出所有与你相关的流程
        $flow = OaFlow::find()->where(['user_id'=>Yii::$app->user->id])->all();
        if(!empty($flow)){
            $list = OaApply::find();
            //使用 任务id和流程步骤数 搜索当前的申请表中匹配的
            foreach($flow as $f){
                $list= $list->orWhere(['task_id'=>$f->task_id,'flow_step'=>$f->step]);
            }
            //按时间倒序
            $list = $list->orderBy('add_time desc')->all();
        }else{
            $list = [];
        }

        $params['list'] = $list;
        return $this->render('todo',$params);
    }

    //相关事项
    public function actionRelated(){
        //检索出所有与你相关的流程  按task_id分组
        $flow = OaFlow::find()->where(['user_id'=>Yii::$app->user->id])->groupBy('task_id')->select('task_id')->all();
        if(!empty($flow)){
            $taskIds = [];
            foreach($flow as $f){
                $taskIds[] = $f->task_id;
            }
            $list = OaApply::find()->where(['task_id'=>$taskIds])->orderBy('add_time desc')->all();
        }else{
            $list = [];
        }


        $params['list'] = $list;
        return $this->render('related',$params);
    }

    /*//完结事项
    public function actionRelated(){
        //检索出所有与你相关的流程  按task_id分组
        $flow = OaFlow::find()->where(['user_id'=>Yii::$app->user->id])->groupBy('task_id')->select('task_id')->all();
        if(!empty($flow)){
            $taskIds = [];
            foreach($flow as $f){
                $taskIds[] = $f->task_id;
            }
            $list = OaApply::find()->where(['task_id'=>$taskIds])->orderBy('add_time desc')->all();
        }else{
            $list = [];
        }


        $params['list'] = $list;
        return $this->render('related',$params);
    }*/


    public function actionDo(){
        $id = Yii::$app->request->get('id',false);
        $apply = OaApply::find()->where(['id'=>$id])->one();
        if($apply){
            $flow = OaFlow::find()->where(['task_id'=>$apply->task_id,'step'=>$apply->flow_step,'user_id'=>Yii::$app->user->id])->one();
            if($flow){
                $model = new OaApplyDoForm();

                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $new = new OaApplyRecord();
                    $new->attributes = $model->attributes;
                    $new->apply_id = $apply->id;
                    $new->flow_id = $flow->id;
                    $new->add_time = date('Y-m-d H:i:s');
                    if($new->save()){
                        $apply->flow_step++;
                        $apply->save();

                        //Yii::$app->session->setFlash()
                        return $this->redirect('/apply/todo');
                    }
                }
                $params['model'] = $model;
                $params['apply'] = $apply;
                $params['flow'] = $flow;
                return $this->render('do',$params);
            }
        }



    }

}
