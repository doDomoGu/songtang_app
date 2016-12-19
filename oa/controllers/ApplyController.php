<?php
namespace oa\controllers;

use oa\components\Func;
use oa\models\OaApply;
use oa\models\OaApplyForm;
use oa\models\OaApplyRecord;
use oa\models\OaFlow;
use oa\models\OaTask;
use oa\models\OaTaskApplyUser;
use yii\web\Response;
use Yii;

class ApplyController extends BaseController
{
    public function actionCreate(){
        $model = new OaApplyForm();

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
