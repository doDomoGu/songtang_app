<?php
namespace oa\controllers;

use Codeception\PHPUnit\Constraint\Page;
use common\components\CommonFunc;
use login\models\UserIdentity;
use oa\components\Func;
use oa\components\OaFunc;
use oa\models\Apply;
use oa\models\ApplyCreateForm;
use oa\models\ApplyDoForm;
use oa\models\ApplyFormContent;
use oa\models\ApplyRecord;
use oa\models\Flow;
use oa\models\Form;
use oa\models\FormItem;
use oa\models\Task;
use oa\models\TaskApplyUser;
use oa\models\TaskCategory;
use oa\models\TaskCategoryId;
use oa\models\TaskForm;
use oa\models\TaskUserWildcard;
use ucenter\models\User;
use yii\bootstrap\Html;
use yii\web\Response;
use Yii;

class ApplyController extends BaseController
{
    public $defaultAction = 'create';
    public function actionCreate222(){
        $model = new ApplyCreateForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $new = new Apply();
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
     * 发起申请 填写页面
     */
    public function actionCreate(){
        $category = Yii::$app->request->get('category',0);

        $model = new ApplyCreateForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $new = new Apply();
            $new->attributes = $model->attributes;
            $new->user_id = Yii::$app->user->id;
            $new->flow_step = 1;
            $new->add_time = date('Y-m-d H:i:s');
            $new->edit_time = date('Y-m-d H:i:s');
            $new->status = 1;
            $new->form_id = intval($new->form_id);

            //检查任务表(task)中有没有需要选择操作人的流程(flow)
            $flowList = Flow::find()->where(['task_id'=>$new->task_id])->all();
            $needUserSelectCount = 0;
            $userSelectedCount = 0;
            $flowUserSelect = Yii::$app->request->post('flow_user',false);
            foreach($flowList as $fl){
                if($fl->user_id==0){
                    $needUserSelectCount++;
                    if(isset($flowUserSelect[$fl->step]) && $flowUserSelect[$fl->step]>0){
                        $userSelectedCount++;
                    }
                }
            }
            if($needUserSelectCount>0 && $userSelectedCount!=$needUserSelectCount){
                echo 'flow user select wrong';exit;
            }

            if($flowUserSelect){
                $new->flow_user = Apply::flowUserArr2Str($flowUserSelect);
            }else{
                $new->flow_user = '';
            }
            if($new->save()){
                $r = new ApplyRecord();
                $r->apply_id = $new->id;
                $r->flow_id = 0;
                $r->step = 0;
                $r->title = $new->title;
                $r->type = 0;
                $r->user_id = $new->user_id;
                $r->result = 0;
                $r->message = $new->message;
                $r->add_time = $new->add_time;
                $attachment = [];

                if(Yii::$app->request->post('attachment_url')){
                    foreach(Yii::$app->request->post('attachment_url') as $a){
                        $tmp = explode('|||',$a);
                        if(count($tmp)==2){
                            $attachment[] = ['url'=>$tmp[0],'name'=>$tmp[1]];
                        }
                    }
                }
                $r->attachment = json_encode($attachment);

                $r->save();


                //表单
                $form = Form::find()->where(['id'=>$new->form_id,'status'=>1])->one();
                if($form){
                    $form_item = Yii::$app->request->post('form_item',false);
                    if($form_item && is_array($form_item)){
                        foreach($form_item as $item_k=>$item_v){
                            $formItem = FormItem::find()->where(['form_id'=>$form->id,'item_key'=>$item_k])->one();
                            if($formItem){
                                $applyFormContent = new ApplyFormContent();
                                $applyFormContent->apply_id = $new->id;
                                $applyFormContent->ord = $formItem->ord;
                                $applyFormContent->item_key = $item_k;
                                $item_value = FormItem::jsonDecodeValue($formItem->item_value);
                                $item_value['value'] = $item_v;
                                $applyFormContent->item_value = $item_value;
                                $applyFormContent->save();
                            }
                        }
                    }
                }


                //生成接下来的流程
                $flows = Flow::find()->where(['task_id'=>$new->task_id])->orderBy('step asc')->all();
                foreach($flows as $flow){
                    $r = new ApplyRecord();
                    $r->apply_id = $new->id;
                    $r->flow_id = 0;
                    $r->step = $flow->step;
                    $r->title = $flow->title;
                    $r->type = $flow->type;
                    if($new->user_id>0){
                        $r->user_id = $new->user_id;
                    }else{
                        $r->user_id = $flowUserSelect[$new->step];
                    }
                    $r->result = 0;
                    $r->message = '';
                    $r->add_time = '0000-00-00 00:00:00';
                    $r->attachment = json_encode([]);
                    $r->save();
                }


                //Yii::$app->session->setFlash()
                return $this->redirect('/apply/my');
            }
        }else{
            $model->task_category = $category;
        }
        $params['model'] = $model;
        $params['tasks'] = Func::getTasksByUid(Yii::$app->user->id);
        $params['taskCategory'] = TaskCategory::getDropdownList();
        if($this->isMobile){
            $this->tabbar_on = 2;
            return $this->render('mobile/create',$params);
        }else
            return $this->render('create',$params);
    }

    /*
     * 发起申请 提交操作
     */

    public function actionDoCreate(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $n = new Apply();
            $n->title = $post['title'];
            $n->task_id  = $post['task_id'];
            $n->message  = $post['message'];
            $n->user_id = Yii::$app->user->id;
            $n->flow_step = 1;
            $n->add_time = date('Y-m-d H:i:s');
            $n->edit_time = date('Y-m-d H:i:s');
            $n->status = 1;
            if($n->save()){
                $r = new ApplyRecord();
                $r->apply_id = $n->id;
                $r->flow_id = 0;
                $r->step = 0;
                $r->title = $n->title;
                $r->type = 0;
                $r->user_id = $n->user_id;
                $r->result = 0;
                $r->message = $n->message;
                $r->add_time = $n->add_time;
                $r->save();


                Yii::$app->getSession()->setFlash('success','发起申请成功！');
                $result = true;
            }else{
                $errormsg = '发起申请失败!';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }


    /*
     * get-task-list 发起申请界面中，根据地区和业态选项获取可用来的申请的任务列表
     * 返回 html
     */
    public function actionGetTaskList(){
        $errormsg = '';
        $result = false;
        $html = '';
        if(Yii::$app->request->isAjax){
            $taskCategory = trim(Yii::$app->request->post('task_category',0));
            $taskCategoryIds = TaskCategoryId::find()->where(['category_id'=>$taskCategory])->all();
            foreach($taskCategoryIds as $taskCategoryId){

                $task = Task::find()->where(['id'=>$taskCategoryId->task_id])->one();
                //1.判断模板状态
                if($task && $task->set_complete == 1 && $task->status == 1){
                    //2.判断模板发起人
                    if(Yii::$app->user->identity->isOaFrontendAdmin){
                        $isAllow = true;
                    }else{
                        $isAllow = false;
                        $userWildcardList = TaskUserWildcard::find()->where(['task_id'=>$task->id])->all();

                        //$params['list'] = $applyUser;
                        $userList = [];
                        foreach($userWildcardList as $uWL){
                            $result = $uWL->getUsers();
                            foreach($result as $r){

                                if($r->id == Yii::$app->user->id){
                                    $isAllow = true;
                                }
                            }
                        }
                    }

                    if($isAllow){
                        $list[$task->id] = $task->title;
                    }

                }
            }


            //$list = Task::getList($district_id,$industry_id,$company_id);
            $html .='<option value="">==请选择==</option>';
            if(!empty($list)){
                foreach($list as $k=>$v){
                    $html.='<option value="'.$k.'">'.$v.'</option>';
                }
            }
            $result = true;
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'html'=>$html];
    }

    public function actionGetTaskList22(){
        $errormsg = '';
        $result = false;
        $html = '';
        if(Yii::$app->request->isAjax){
            $district_id = trim(Yii::$app->request->post('district_id',0));
            $industry_id = trim(Yii::$app->request->post('industry_id',0));
            $company_id = trim(Yii::$app->request->post('company_id',0));
            $list = Task::getList($district_id,$industry_id,$company_id);
            $html .='<option value="">==请选择==</option>';
            if(!empty($list)){
                foreach($list as $k=>$v){
                    $html.='<option value="'.$k.'">'.$v.'</option>';
                }
            }
            $result = true;
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'html'=>$html];
    }


    /*
     * get-task-preview 获取申请任务的预览信息
     * 返回 html
     */
    public function actionGetTaskPreview(){
        $errormsg = '';
        $result = false;
        $html = '';
        $formSelectHtml = '';
        $formContentHtml = '';
        if(Yii::$app->request->isAjax){
            $task_id = trim(Yii::$app->request->post('task_id',false));
            $task = Task::find()->where(['id'=>$task_id])->one();
            if($task){
                $flows = Flow::find()->where(['task_id'=>$task_id])->orderBy('step asc')->all();
                if(!empty($flows)){
                    $html.='<section class="task-preview-section">'.
                            '<h1>申请表流程：</h1>';
                    //$html.='<h3>对应地区：'.($task->area->name).'  对应业态：'.($task->business->name).'</h3>';
                    $userItems = [];
                    $i = 0;
                    foreach($flows as $f){
                        $i++;
                        if($f->user_id>0){
                            if($f->user){
                                $operation_user = $f->user->getFullRoute();
                            }else{
                                $operation_user = 'N/A';
                            }
                        }else{
                            if(empty($userItems)){
                                $userItems = User::getItems();
                            }
                            $operation_user = '';
                            //$operation_user = '[由发起人选择]';
                            $operation_user .= Html::dropDownList('flow_user['.$f->step.']','',$userItems,['class'=>"flow-user"]);;
                        }

                        $htmlOne = '<li class="flow done">';
                        $htmlOne.= '<span class="approval-title">'.Html::img('/images/main/apply/modal-approval-'.$i.'.png').' '.$f->title.'</span>';
                        $htmlOne.= '<span class="approval-sign">'.$operation_user.'</span>';
                        /*$htmlOne.= '<div class="task-preview-step">步骤'.$f->step.'</div>';
                        $htmlOne.= '<div>标题：'.$f->title.'</div>';
                        $htmlOne.= '<div>类型：'.$f->typeName.'</div>';
                        $htmlOne.= '<div>转发：'.($f->enable_transfer==1?'允许':'禁止').'</div>';

                        $htmlOne.= '<div>操作人：'.$operation_user.'</div>';*/
                        $htmlOne.= '</li>';
                        $html .= $htmlOne;
                    }
                    $html.= '</section>';
                    $result = true;
                }else{
                    $errormsg = '申请任务表没有设置流程！';
                }

                $taskForms = TaskForm::find()->where(['task_id'=>$task_id])->all();
                $formList = [];
                foreach($taskForms as $tf){

                    $form = Form::find()->where(['id'=>$tf->form_id,'status'=>1,'set_complete'=>1])->one();
                    if($form){
                        $formList[$form->id] = $form->title;
                    }
                }


                //$list = Task::getList($district_id,$industry_id,$company_id);

                if(!empty($formList)){
                    $formSelect = '';
                    foreach($formList as $k=>$v){
                        $formSelect = $formSelect?$formSelect:$k;
                        $formSelectHtml .='<option value="'.$k.'">'.$v.'</option>';
                    }
                    //获取表单内容
                    $formItems = FormItem::find()->where(['form_id'=>$formSelect,'status'=>1])->orderBy('ord asc')->all();
                    if($formItems){
                        $formContentHtml .='<section class="task-form-section">'.
                            '<h1>申请表-表单：</h1>';
                        //$i = 0;
                        foreach($formItems as $item){

                                $valueArr = FormItem::jsonDecodeValue($item->item_value,$item->item_key,true);
                                //$i++;
                                $htmlOne = '<li class="form-item">';
                                $htmlOne.= '<span class="item-label">'.$valueArr['label'].'</span>';
                                $htmlOne.= '<span class="item-content">'.$valueArr['itemContent'].'</span>';
                                /*$htmlOne.= '<div class="task-preview-step">步骤'.$f->step.'</div>';
                                $htmlOne.= '<div>标题：'.$f->title.'</div>';
                                $htmlOne.= '<div>类型：'.$f->typeName.'</div>';
                                $htmlOne.= '<div>转发：'.($f->enable_transfer==1?'允许':'禁止').'</div>';

                                $htmlOne.= '<div>操作人：'.$operation_user.'</div>';*/
                                $htmlOne.= '</li>';
                                $formContentHtml .= $htmlOne;

                        }
                        $formContentHtml .= '</section>';
                    }
                }else{
                    $formSelectHtml .='<option value="">==请选择==</option>';
                }

            }else{
                $errormsg = '申请任务表不存在！';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'html'=>$html,'formSelectHtml'=>$formSelectHtml,'formContentHtml'=>$formContentHtml];
    }

    public function actionPrint(){
        $this->layout = 'main_blank';
        $html  = '';
        $id = trim(Yii::$app->request->get('id',false));
        $apply = Apply::find()->where(['id'=>$id])->one();
        if($apply){
            $html = $this->getHtmlByApply($apply);
        }
        $params['html'] = $html;
        return $this->render('print',$params);
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
            $apply = Apply::find()->where(['id'=>$id])->one();
            if($apply){
                $result = true;
                $html = $this->getHtmlByApply($apply);

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

    public function getHtmlByApply($apply){
        $html = '';

        $html .= '<section id="apply-top">'.
            '<span class="apply_user"> 申请人：</span><span class="apply_user-txt">'.$apply->applyUser->name.'</span>'.
            '<span class="apply_position"> 部门：</span><span class="apply_position-txt">'.$apply->applyUser->getFullPositionRoute().'</span>'.
            '</section>';
        $html .= '<section id="apply-header">'.
            '<span class="title">'.Html::img('/images/main/apply/modal-title.png').' 申请主题：</span><span class="title-txt">'.$apply->title.'</span>'.
            '<span class="date">'.Html::img('/images/main/apply/modal-date.png').' 申请日期：</span><span class="date-txt">'. date('Y-m-d',strtotime($apply->add_time)).'</span>'.
            '</section>';

        //1.发起申请
        $form_id = $apply->form_id;
        $applyFromContent = ApplyFormContent::find()->where(['apply_id'=>$apply->id])->orderBy('ord asc')->all();
        if(!empty($applyFromContent)){
            $html.= '<section id="apply-form-content">';
            foreach($applyFromContent as $afc){
                $html .= '<span class="form-content-label" style="width:'.$afc['label_width'].'px;"></span>';
                $html .= '<span class="form-content-input"></span>';
            }
            $html.= '</section>';
        }

        $html .= '<section id="apply-message">' .
            '<span class="message-title">'.Html::img('/images/main/apply/modal-message.png').' 申请内容：</span><span class="message-txt">'.(str_replace("\r\n",'<br/>',$apply->message)).'</span>'.
            '</section>';
        $html .= '<section id="apply-main">' .
            '<div class="apply-main-title">'.
            '<span class="apply-title apply-user">'.Html::img('/images/main/apply/modal-user.png').' 审批流程</span>'.
            '<span class="apply-title apply-sign-head">'.Html::img('/images/main/apply/modal-sign-head.png').' 签名'.Html::img('/images/main/apply/create-icon-2.png',['class'=>'operation-icon']).'</span>'.
            '<span class="apply-title apply-approval-head">'.Html::img('/images/main/apply/modal-approval-head.png').' 批示'.Html::img('/images/main/apply/create-icon-2.png',['class'=>'operation-icon']).'</span>'.
            '<span class="apply-title apply-message-head">'.Html::img('/images/main/apply/modal-approval-head.png').' 批注'.Html::img('/images/main/apply/create-icon-2.png',['class'=>'operation-icon']).'</span>'.
            '<span class="apply-title apply-time-head">'.Html::img('/images/main/apply/modal-approval-head.png').' 时间'.Html::img('/images/main/apply/create-icon-2.png',['class'=>'operation-icon']).'</span>'.
            '</div>';


        //2.操作记录
        $records = ApplyRecord::find()->where(['apply_id'=>$apply->id])->orderBy('step asc')->all();
        $recordsDone = [];
        $recordsTodo = [];
        foreach($records as $r){
            if($r->step==0){
                continue;
            }else if($r->step < $apply->flow_step){
                $recordsDone[] = $r;
            }else{
                $recordsTodo[] = $r;
            }
        }

        if(!empty($recordsDone)){
            $i = 1;
            foreach($recordsDone as $r){
                if($r->step==0) continue;
/*                if($r->flow->user_id>0){
                    $username = $r->flow->user->name;
                }else{
                    $username = '[自由选择]';
                }*/

                $flow_user = CommonFunc::getByCache(UserIdentity::className(),'findIdentityOne',[$r->user_id],'ucenter:user/identity');
                $username = $flow_user?$flow_user->name:'N/A';

                $htmlOne = '<li class="flow done">';
                $htmlOne.= '<span class="r-done approval-title">'.Html::img('/images/main/apply/modal-approval-'.$i.'.png').' '.$r->flow->title.'</span>';
                $htmlOne.= '<span class="r-done approval-sign">'.$username.'</span>';
                $htmlOne.= '<span class="r-done approval-result">'.Flow::getResultCn($r->flow->type,$r->result).'</span>';
                $htmlOne.= '<span class="r-done approval-message">'.($r->message?$r->message:'&nbsp').'</span>';
                $htmlOne.= '<span class="r-done approval-time">'.substr($r->add_time,0,-3).'</span>';
                $htmlOne.= '</li>';
                /*$htmlOne = '<li class="flow">';
                $htmlOne.= '<div>步骤'.$r->flow->step.'</div>';
                $htmlOne.= '<div>标题：<b>'.$r->flow->title.'</b>  操作类型：<b>'.$r->flow->typeName.'</b></div>';


                $htmlOne.= '<div>操作人：<b>'.$username.'</b> 时间: <b>'.$r->add_time.'</b> 结果：<b>'.Flow::getResultCn($r->flow->type,$r->result).'</b></div>';
                $htmlOne.= '<div>备注信息：<b>'.$r->message.'</b></div>';
                $htmlOne.= '</li>';*/

                $html .= $htmlOne;
                $i++;
            }
        }

        //3.剩余未完成操作  * 只有申请表(apply)状态为执行中(status=1)
        if($apply->status==1 && !empty($recordsTodo)){
            $i = 1;
            foreach($recordsTodo as $r){
                //$username = Apply::getOperationUser($apply,$f);
                /*if($f->user_id>0){
                    $username = $f->user->name;
                }else{
                    $username = '[自由选择]';
                }*/

                $htmlOne = '<li class="flow not-do">';
                $htmlOne.= '<span class="r-not-do approval-title">'.Html::img('/images/main/apply/modal-approval-'.$i.'.png').' '.$r->title.'</span>';
                $htmlOne.= '<span class="r-not-do approval-sign">'.$r->user->name.'</span>';
                $htmlOne.= '<span class="r-not-do approval-result">还未操作</span>';
                $htmlOne.= '<span class="r-not-do approval-message">--</span>';
                $htmlOne.= '<span class="r-not-do approval-time">--</span>';
                /*$htmlOne.= '<div>步骤'.$f->step.' 还未操作</div>';
                $htmlOne.= '<div>标题：<b>'.$f->title.'</b>  操作类型：<b>'.$f->typeName.'</b></div>';

                $htmlOne.= '<div>操作人：<b>'.$username.'</b> </div>';*/
                $htmlOne.= '</li>';
                $html .= $htmlOne;
                $i++;
            }
        }

        //4. 打印
        $html .= '<div id="a-print" class="hidden-print"><a data-id="'.$apply->id.'" type="button" class="print-btn">打印</a></div>';

        $html .= '</section>';

        return $html;
    }

    //我的申请
    public function actionMy(){
        $list = Apply::getMyList();

        $params['list'] = $list;
        if($this->isMobile){
            $this->tabbar_on = 2;
            return $this->render('mobile/my',$params);
        }else
            return $this->render('my',$params);
    }

    //待办事项
    public function actionTodo(){
        $list = Apply::getTodoList();

        $params['list'] = $list;
        if($this->isMobile){
            $this->tabbar_on = 1;
            return $this->render('mobile/todo',$params);
        }else
            return $this->render('todo',$params);
    }

    //相关事项
    public function actionRelated(){
        $list = Apply::getRelatedList();


        $params['list'] = $list;
        if($this->isMobile){
            $this->tabbar_on = 1;
            return $this->render('mobile/related',$params);
        }else
            return $this->render('related',$params);
    }

    //办结事项
    public function actionDone(){
        //检索出所有与你相关的流程  按task_id分组

        $list = Apply::getDoneList();
        $params['list'] = $list;
        if($this->isMobile){
            $this->tabbar_on = 1;
            return $this->render('mobile/done',$params);
        }else
            return $this->render('done',$params);
    }

    //完结事项
    public function actionFinish(){
        //检索出所有与你相关的流程  按task_id分组
        $list = Apply::getFinishList();
        $params['list'] = $list;
        if($this->isMobile){
            $this->tabbar_on = 1;
            return $this->render('mobile/finish',$params);
        }else
            return $this->render('done',$params);
    }


    /*
     * 流程信息 界面
     */
    public function actionInfo(){
        $id = Yii::$app->request->get('id',false);
        $apply = Apply::find()->where(['id'=>$id])->one();
        if($apply){

            $records = ApplyRecord::find()->where(['apply_id'=>$id])->all();
            $curStep = $apply->flow_step;
            $flowNotDo = Flow::find()->where(['task_id'=>$apply->task_id])->andWhere(['>=','step',$curStep])->all();


            $params['apply'] = $apply;
            $params['records'] = $records;
            $params['flowNotDo'] = $flowNotDo;
            if($this->isMobile){
                $this->tabbar_on = 1;
                return $this->render('mobile/info',$params);
            }else
                echo 'not found';exit;
        }else{
            echo '申请表ID错误!';
            return false;
        }
    }

    /*
     * 操作办事 界面
     */
    public function actionOperation(){
        $id = Yii::$app->request->get('id',false);
        $apply = Apply::find()->where(['id'=>$id])->one();
        if($apply){
            //1.发起申请
            $html = '<li><div>发起申请</div><div>操作人：<b>'.$apply->applyUser->name.'</b> 时间：<b>'.$apply->add_time.' </b></div><div>申请信息：<b>'.$apply->message.'</b></div>';

            $record0 = ApplyRecord::find()->where(['apply_id'=>$id,'step'=>0])->one();
            if($record0){
                $attchList = json_decode($record0->attachment,true);
                if($attchList){
                    $html .= '<div><span style="display:block;width:40px;float:left;">附件: </span><span style="display: block;width:500px;float:left;">';
                    foreach($attchList as $a){
                        $html .= '<div>'.Html::a($a['name'],OaFunc::getResourcePath($a['url']),['style'=>'color:#333;text-decoration:underline;']).'</div>';
                    }
                    $html .= '</span></div>';
                }
            }

            $html .= '</li>';

            //2.操作记录
            $records = ApplyRecord::find()->where(['apply_id'=>$id])->all();
            if(!empty($records)){
                foreach($records as $r){
                    if($r->step==0) continue;
                    $htmlOne = '<li>';
                    $htmlOne.= '<div>步骤'.$r->flow->step.'</div>';
                    $htmlOne.= '<div>标题：<b>'.$r->flow->title.'</b>  操作类型：<b>'.$r->flow->typeName.'</b></div>';
                    $username = Apply::getOperationUser($apply,$r->flow);
                    $htmlOne.= '<div>操作人：<b>'.$username.'</b> 时间: <b>'.$r->add_time.'</b> </div><div>结果：<b>'.Flow::getResultCn($r->flow->type,$r->result).'</b></div>';
                    $htmlOne.= '<div>备注信息：<b>'.$r->message.'</b></div>';
                    $attchList = json_decode($r->attachment,true);
                    if($attchList){
                        $htmlOne .= '<div><span style="display:block;width:40px;float:left;">附件: </span><span style="display: block;width:500px;float:left;">';
                        foreach($attchList as $a){
                            $htmlOne .= '<div>'.Html::a($a['name'],OaFunc::getResourcePath($a['url']),['style'=>'color:#333;text-decoration:underline;']).'</div>';
                        }
                        $htmlOne .= '</span></div>';
                    }


                    $htmlOne.= '</li>';
                    $html .= $htmlOne;
                }
            }

            //3.剩余未完成操作
            $html2 = '';
            $curStep = $apply->flow_step;
            $flowNotDo = Flow::find()->where(['task_id'=>$apply->task_id])->andWhere(['>','step',$curStep])->all();
            foreach($flowNotDo as $f){
                $htmlOne = '<li class="not-do">';
                $htmlOne.= '<div>步骤'.$f->step.' 还未操作</div>';
                $htmlOne.= '<div>标题：<b>'.$f->title.'</b>  操作类型：<b>'.$f->typeName.'</b></div>';
                $username = Apply::getOperationUser($apply,$f);
                $htmlOne.= '<div>操作人：<b>'.$username.'</b> </div>';
                $htmlOne.= '</li>';
                $html2 .= $htmlOne;
            }
            $flow = Flow::find()->where(['task_id'=>$apply->task_id,'step'=>$apply->flow_step])->one();
            $flag = false;
            if($flow->user_id>0){
                if($flow->user_id==Yii::$app->user->id){
                    $flag = true;
                }
            }else{
                $flowUser = Apply::flowUserStr2Arr($apply->flow_user);
                if(isset($flowUser[$apply->flow_step]) && $flowUser[$apply->flow_step] == Yii::$app->user->id){
                    $flag = true;
                }
            }


            if($flag){
                $model = new ApplyDoForm();
                $model->result = 1;


                $params['model'] = $model;
                $params['apply'] = $apply;
                $params['flow'] = $flow;
                $params['records'] = $records;
                $params['flowNotDo'] = $flowNotDo;
                $params['html'] = $html;
                $params['html2'] = $html2;
                if($this->isMobile){
                    $this->tabbar_on = 1;
                    return $this->render('mobile/operation',$params);
                }else
                    return $this->render('operation',$params);
            }else{
                echo '申请表流程错误!';
                return false;
            }
        }else{
            echo '申请表ID错误!';
            return false;
        }
    }


    /*
     * 操作办事  提交操作
     */
    public function actionDoOperation(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $apply = Apply::find()->where(['id'=>$post['apply_id']])->one();
            if($apply){
                /*$flow = Flow::find()->where(['task_id'=>$apply->task_id,'step'=>$apply->flow_step])->one();
                $flag = false;
                if($flow->user_id>0){
                    if($flow->user_id==Yii::$app->user->id){
                        $flag = true;
                    }
                }else{
                    $flowUser = Apply::flowUserStr2Arr($apply->flow_user);
                    if(isset($flowUser[$apply->flow_step]) && $flowUser[$apply->flow_step] == Yii::$app->user->id){
                        $flag = true;
                    }
                }
                if($flag){*/

                //获取apply_record表中当前的操作的步骤数
                $curRecord = ApplyRecord::find()->where(['apply_id'=>$apply->id])->andWhere(['>','add_time',0])->orderBy('step asc')->one();
                if($curRecord && $curRecord->step == $apply->flow_step){

                    $curRecord->result = $post['result'];
                    $curRecord->message = $post['message'];
                    $curRecord->attachment = isset($post['attachment'])?json_encode($post['attachment']):json_encode([]);

                    $curRecord->add_time = date('Y-m-d H:i:s');
                    if($curRecord->save()){
                        //操作类型为 1 approval审核 和 3 execute执行  结果为0 进行打回操作
                        if($curRecord->result==0 && in_array($curRecord->type,[Flow::TYPE_APPROVAL,Flow::TYPE_EXECUTE])){

                            $apply->status = Apply::STATUS_FAILURE;

                            //打回就直接置为失败
                            /*if($flow->back_step==0){
                                //打回到发起者 改为失败状态
                                $apply->status = Apply::STATUS_FAILURE;
                            }else{
                                $apply->flow_step = $flow->back_step;
                            }*/
                        }else{
                            //查找是否还有后续流程
                            $recordTodo = ApplyRecord::find()->where(['apply_id'=>$apply->id])->andWhere(['>','step',$curRecord->step])->one();
                            if($recordTodo){
                                $apply->flow_step++;
                            }else{
                                // 没有就完成此申请  改为成功状态
                                $apply->status= Apply::STATUS_SUCCESS;
                            }
                        }
                        $apply->edit_time = date('Y-m-d H:i:s');
                        $apply->save();

                        Yii::$app->getSession()->setFlash('success','操作成功！');
                        $result = true;
                    }else{
                        $errormsg = '操作失败，请重试!';
                    }
                }else{
                    $errormsg = '申请表流程错误!';
                }
            }else{
                $errormsg = '申请表ID错误!';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }


    /*
 * 操作办事 撤销申请
 */
    public function actionDel()
    {
        $errormsg = '';
        $result = false;
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $apply = Apply::find()->where(['id'=>$id,'user_id'=>Yii::$app->user->id,'status'=>Apply::STATUS_NORMAL])->one();
            if($apply){
                $apply->status = Apply::STATUS_DELETE;
                $apply->save();
                $result = true;
            }else{
                $errormsg = '申请表状态错误!';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }

        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

}
