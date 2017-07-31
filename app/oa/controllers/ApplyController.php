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
use oa\models\FormCategory;
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

                    $items = FormItem::find()->where(['form_id'=>$form->id])->orderBy('ord asc')->all();
                    foreach($items as $item){
                        $applyFormContent = new ApplyFormContent();
                        $applyFormContent->apply_id = $new->id;
                        $applyFormContent->ord = $item->ord;
                        $applyFormContent->item_key = $item->item_key;
                        $item_value = FormItem::jsonDecodeValue($item->item_value);
                        if(isset($form_item[$item->item_key])){
                            $item_value['value'] = $form_item[$item->item_key];
                        }
                        $applyFormContent->item_value = json_encode($item_value);
                        $applyFormContent->save();
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
                    if($flow->user_id>0){
                        $r->user_id = $flow->user_id;
                    }else{
                        $r->user_id = $flowUserSelect[$flow->step];
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
            $category_id = trim(Yii::$app->request->post('category_id',false));
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
                        $formCategory = FormCategory::find()->where(['form_id'=>$form->id,'category_id'=>$category_id])->one();
                        if($formCategory){
                            $formList[$form->id] = $form->title;
                        }
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
                    $formContentHtml = FormItem::getHtmlByForm($formSelect);

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


    public function actionGetFormPreview(){
        $errormsg = '';
        $result = false;
        $html = '';
        if(Yii::$app->request->isAjax){
            $form_id = trim(Yii::$app->request->post('form_id',false));
            //获取表单内容
            $html = FormItem::getHtmlByForm($form_id);
            $result = true;
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'html'=>$html];
    }

    public function getHtmlByForm($form_id){
        $formContentHtml = '';
        $formItems = FormItem::find()->where(['form_id'=>$form_id,'status'=>1])->orderBy('ord asc')->all();
        if($formItems) {
            $formContentHtml .= '<section class="task-form-section">' .
                '<h1>申请表-表单：</h1>';
            //$i = 0;
            foreach ($formItems as $item) {

                $valueArr = FormItem::jsonDecodeValue($item->item_value, $item->item_key, true);
                //$i++;
                $htmlOne = '<li class="form-item type-'.$valueArr['type'].'">';
                if($valueArr['label_width']>0) {
                    $htmlOne .= '<span class="item-label" style="width:' . $valueArr['label_width'] . 'px">' . $valueArr['label'] . '</span>';
                }
                $htmlOne .= '<span class="item-content" style="width:' . $valueArr['input_width'] . 'px">' . $valueArr['itemContent'] . '</span>';
                /*$htmlOne.= '<div class="task-preview-step">步骤'.$f->step.'</div>';
                $htmlOne.= '<div>标题：'.$f->title.'</div>';
                $htmlOne.= '<div>类型：'.$f->typeName.'</div>';
                $htmlOne.= '<div>转发：'.($f->enable_transfer==1?'允许':'禁止').'</div>';

                $htmlOne.= '<div>操作人：'.$operation_user.'</div>';*/
                $htmlOne .= '</li>';
                $formContentHtml .= $htmlOne;

            }
            $formContentHtml .= '</section>';
        }
        return $formContentHtml;

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

        //1.发起申请 的 表单内容
        //$form_id = $apply->form_id;
        $applyFromContent = ApplyFormContent::find()->where(['apply_id'=>$apply->id])->orderBy('ord asc')->all();
        if(!empty($applyFromContent)){
            $html.= '<section id="apply-form-content" class="task-form-section">';
            $html.= FormItem::getHtmlByFormItem($applyFromContent);
            /*
            foreach($applyFromContent as $afc){
                $valueArr = FormItem::jsonDecodeValue($afc->item_value,$afc->item_key,true);
                $html.= '<span class="form-content-item">';
                if($valueArr['label_width']>0) {
                    $html .= '<span class="form-content-label" style="width:' . $valueArr['label_width'] . 'px;">' . $valueArr['label'] . '</span>';
                }
                $html .= '<span class="form-content-input" style="width:'.$valueArr['input_width'].'px;">'.$valueArr['itemContent'].'</span>';

                $html .='</span>';
            }*/
            $html.= '</section>';
        }

        $html .= '<section id="apply-message">' .
            '<span class="message-title">'.Html::img('/images/main/apply/modal-message.png').' 申请内容：</span><span class="message-txt">'.(str_replace("\r\n",'<br/>',$apply->message)).'</span>'.
            '</section>';


        //2.操作步骤记录
        //列表头
        $html .= '<section id="apply-main">' .
            '<div class="apply-main-title">'.
            '<span class="apply-title apply-user">'.Html::img('/images/main/apply/modal-user.png').' 审批流程</span>'.
            '<span class="apply-title apply-sign-head">'.Html::img('/images/main/apply/modal-sign-head.png').' 签名'.Html::img('/images/main/apply/create-icon-2.png',['class'=>'operation-icon']).'</span>'.
            '<span class="apply-title apply-approval-head">'.Html::img('/images/main/apply/modal-approval-head.png').' 批示'.Html::img('/images/main/apply/create-icon-2.png',['class'=>'operation-icon']).'</span>'.
            '<span class="apply-title apply-message-head">'.Html::img('/images/main/apply/modal-approval-head.png').' 批注'.Html::img('/images/main/apply/create-icon-2.png',['class'=>'operation-icon']).'</span>'.
            '<span class="apply-title apply-time-head">'.Html::img('/images/main/apply/modal-approval-head.png').' 时间'.Html::img('/images/main/apply/create-icon-2.png',['class'=>'operation-icon']).'</span>'.
            '</div>';


        //列表内容
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
                $htmlOne.= '<span class="r-done approval-title">'.Html::img('/images/main/apply/modal-approval-'.$i.'.png').' '.$r->title.'</span>';
                $htmlOne.= '<span class="r-done approval-sign">'.$username.'</span>';
                $htmlOne.= '<span class="r-done approval-result">'.Flow::getResultCn($r->type,$r->result).'</span>';
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

                $flow_user = CommonFunc::getByCache(UserIdentity::className(),'findIdentityOne',[$r->user_id],'ucenter:user/identity');
                $username = $flow_user?$flow_user->name:'N/A';

                $htmlOne = '<li class="flow not-do">';
                $htmlOne.= '<span class="r-not-do approval-title">'.Html::img('/images/main/apply/modal-approval-'.$i.'.png').' '.$r->title.'</span>';
                $htmlOne.= '<span class="r-not-do approval-sign">'.$username.'</span>';
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

        //4. 打印按钮
        $html .= '<div id="a-print" class="hidden-print">
                <a data-id="'.$apply->id.'" type="button" class="print-btn">打印</a>';
        if(in_array($apply->form_id,[12,13,14])) {
            $html .= '<a data-id="' . $apply->id . '" type="button" class="print2-btn">财务表格导出</a>';
        }
        $html .= '</div>';

        $html .= '</section>';

        return $html;
    }

    public function actionFinanceExport(){
        //require_once '../../../common/extensions/phpexcel/PHPExcel.php';

        $id = Yii::$app->request->get('id');
        $apply = Apply::find()->where(['id'=>$id])->one();

        if($apply->form_id == 12){
            $this->export12($apply);
        }else if($apply->form_id == 13){
            $this->export13($apply);
        }else if($apply->form_id == 14){
            $this->export14($apply);
        }

        Yii::$app->end();

    }

    public function export12($apply){
        $user = User::find()->where(['id'=>$apply->user_id])->one();

        $formContent = ApplyFormContent::find()->where(['apply_id'=>$apply->id])->all();
        $options = [];
        foreach($formContent as $item){
            $options[$item->item_key] = FormItem::jsonDecodeValue($item->item_value);
        }

        $company = $options['company']['input_options'][$options['company']['value']];

        $project = [];
        $pinAll = 0;
        $projectOptions = $options['project']['input_options'];
        foreach($projectOptions as $opt){
            $optArr = explode(':',$opt);
            if($optArr[0]=='item'){
                $itemArr = explode('|',$optArr[4]);
            }
        }
        foreach($options['project']['value'] as $p){
            if($p['item']!==''){
                $project[] = ['item'=>$itemArr[$p['item']],'pin'=>$p['pin'],'price'=>$p['price']];
                $pinAll += intval($p['pin']);
            }
        }



        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getActiveSheet()->mergeCells('A1:F2');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '日常费用报销单');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->mergeCells('G1:I3');
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Photo');
        $objDrawing->setDescription('Photo');
        $objDrawing->setPath('../web/images/code.jpg');
        $objDrawing->setHeight(19);
        $objDrawing->setWidth(108);
        $objDrawing->setCoordinates('G1');
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'V2.0');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', '申请日期');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', '费用部门');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', '附件');

        $objPHPExcel->getActiveSheet()->mergeCells('B4:C4');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', substr($apply->add_time,0,10));
        $objPHPExcel->getActiveSheet()->mergeCells('E4:F4');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', $user->departmentFullRoute);
        $objPHPExcel->getActiveSheet()->mergeCells('H4:I4');
        /*$record = ApplyRecord::find()->where(['apply_id'=>$apply->id,'step'=>0])->one();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', $record->attacment!='[]'?'有':'无');*/


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', '报销人员');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', '职务');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', '报销公司');

        $objPHPExcel->getActiveSheet()->mergeCells('B5:C5');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', $user->username);
        $objPHPExcel->getActiveSheet()->mergeCells('E5:F5');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', $user->position->name);
        $objPHPExcel->getActiveSheet()->mergeCells('H5:I5');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H5', $company);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', '同行人员');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D6', '报销事由');

        $objPHPExcel->getActiveSheet()->mergeCells('B6:C6');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B6', $options['people']['value']);
        $objPHPExcel->getActiveSheet()->mergeCells('E6:I6');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E6', $options['reason']['value']);


        $objPHPExcel->getActiveSheet()->mergeCells('B7:D7');
        $objPHPExcel->getActiveSheet()->mergeCells('F7:I7');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7', '序号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B7', '项目');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E7', '凭证数量');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F7', '合计金额');

        $objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F7')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $i = 1;
        $l = 8;
        foreach($project as $p){
            $objPHPExcel->getActiveSheet()->mergeCells('B'.$l.':D'.$l);
            $objPHPExcel->getActiveSheet()->mergeCells('F'.$l.':I'.$l);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, $i);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$l, $p['item']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, $p['pin']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$l, $p['price']);
            $l++;
            $i++;
        }

        $objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':D'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '合计金额');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, $pinAll);


        $objPHPExcel->getActiveSheet()->mergeCells('F'.$l.':I'.$l);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$l, $options['all']['value']);
        $objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $l++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '借款抵扣');
        $objPHPExcel->getActiveSheet()->mergeCells('B'.$l.':D'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, '结算金额');
        $objPHPExcel->getActiveSheet()->mergeCells('F'.$l.':I'.$l);

        $l++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '收款方式');
        $objPHPExcel->getActiveSheet()->mergeCells('B'.$l.':D'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, '大写');
        $objPHPExcel->getActiveSheet()->mergeCells('F'.$l.':I'.$l);

        $l++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '开户银行');
        $objPHPExcel->getActiveSheet()->mergeCells('B'.$l.':D'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, '账号');
        $objPHPExcel->getActiveSheet()->mergeCells('F'.$l.':I'.$l);




        /*


// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);*/


// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        $filename = 'songtangoa-'.date('Y-m-dTH:i:s');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function export13($apply){
        $user = User::find()->where(['id'=>$apply->user_id])->one();

        $formContent = ApplyFormContent::find()->where(['apply_id'=>$apply->id])->all();
        $options = [];
        foreach($formContent as $item){
            $options[$item->item_key] = FormItem::jsonDecodeValue($item->item_value);
        }

        $company = $options['company']['input_options'][$options['company']['value']];

        $project = [];
        $pinAll = 0;
        $priceAll = 0;

        $projectOptions = $options['project']['input_options'];
        foreach($projectOptions as $opt){
            $optArr = explode(':',$opt);
            if($optArr[0]=='item'){
                $itemArr = explode('|',$optArr[4]);
            }
        }
        foreach($options['project']['value'] as $p){
            if($p['item']!=''){
                $project[] = ['item'=>$itemArr[$p['item']],'pin'=>$p['pin'],'price'=>$p['price']];
                $pinAll += intval($p['pin']);
                $priceAll += $p['price'];
            }
        }
        $priceAll = number_format($priceAll*100/100,2);

$arr = ['A','B','C','D','E','F','G','H','I'];

        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();



        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName("微软雅黑")->setSize(10)->setBold(true);

        //$objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_NONE);

        $objSheet = $objPHPExcel->getActiveSheet();


        /*foreach($arr as $a) {
            $objSheet->getStyle($a . '13')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_NONE);
            $objSheet->getStyle($a . '14')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_NONE);
            $objSheet->getStyle($a . '15')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_NONE);
            $objSheet->getStyle($a . '16')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_NONE);
        }*/


        $objSheet->getColumnDimension('A')->setWidth(61/6); //设置列宽
        $objSheet->getColumnDimension('B')->setWidth(62/6); //设置列宽
        $objSheet->getColumnDimension('C')->setWidth(58/6); //设置列宽
        $objSheet->getColumnDimension('D')->setWidth(68/6); //设置列宽
        $objSheet->getColumnDimension('E')->setWidth(68/6); //设置列宽
        $objSheet->getColumnDimension('F')->setWidth(56/6); //设置列宽
        $objSheet->getColumnDimension('G')->setWidth(62/6); //设置列宽
        $objSheet->getColumnDimension('H')->setWidth(62/6); //设置列宽
        $objSheet->getColumnDimension('I')->setWidth(180/6); //设置列宽

        for($ii = 1;$ii<17;$ii++){
            $objSheet->getRowDimension($ii)->setRowHeight(16); //设置行高
        }
        for($ii = 17;$ii<33;$ii++){
            $objSheet->getRowDimension($ii)->setRowHeight(18); //设置行高
        }




        $objPHPExcel->getActiveSheet()->mergeCells('A1:I13');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','票据粘贴区域，请粘贴单据后剪切………');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);

        $objPHPExcel->getActiveSheet()->mergeCells('A14:I14');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A14','请沿此线剪切单据，然后粘贴在报销单左上角');
        $objPHPExcel->getActiveSheet()->getStyle('A14')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('A14')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        foreach($arr as $a) {
            $objSheet->getStyle($a . '1')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_DASHED);
        }

        for($ii=1;$ii<=14;$ii++){
            $objSheet->getStyle('A'.$ii)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_DASHED);
            $objSheet->getStyle('I'.$ii)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_DASHED);
        }

        foreach($arr as $a) {
            $objSheet->getStyle($a . '14')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_DASHED);
        }

        foreach($arr as $a) {
            $objSheet->getStyle($a . '14')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_NONE);

        }

        $objPHPExcel->getActiveSheet()->mergeCells('A15:I16');

        foreach($arr as $a){
            $objSheet->getStyle($a.'17')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }

        $objSheet->getStyle('A17')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F17')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I17')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A18')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F18')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I18')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        $objSheet->getStyle('A18')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('B18')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('C18')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D18')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('E18')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F18')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);





        $objPHPExcel->getActiveSheet()->mergeCells('A17:F18');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A17', '差旅费报销单');
        $objPHPExcel->getActiveSheet()->getStyle('A17')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A17')->getFont()->setName('微软雅黑')->setSize(16)->setBold(true);

        $objPHPExcel->getActiveSheet()->mergeCells('A19:F19');
        $objSheet->getStyle('A19')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F19')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I19')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);


        foreach($arr as $a){
            $objSheet->getStyle($a.'20')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A20')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A20')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('C20')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D20')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F20')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('G20')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I20')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        foreach($arr as $a){
            $objSheet->getStyle($a.'21')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A21')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A21')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('C21')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D21')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F21')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('G21')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I21')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        foreach($arr as $a){
            $objSheet->getStyle($a.'22')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A22')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A22')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('C22')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D22')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F22')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('G22')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I22')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        foreach($arr as $a){
            $objSheet->getStyle($a.'23')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A23')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A23')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('C23')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D23')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I23')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        foreach($arr as $a){
            $objSheet->getStyle($a.'24')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A24')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A24')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D24')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('E24')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I24')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);




        $objPHPExcel->getActiveSheet()->mergeCells('G17:I19');
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Photo');
        $objDrawing->setDescription('Photo');
        $objDrawing->setPath('../web/images/code2.png');
        $objDrawing->setCoordinates('G17');
        $objDrawing->setHeight(40);
        //$objDrawing->setWidth(108);
        $objDrawing->setOffsetX(20);
        $objDrawing->setOffsetY(20);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        /*$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);*/

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A19', 'V2.0');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A20', '申请日期');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D20', '费用部门');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G20', '附件');

        $objPHPExcel->getActiveSheet()->mergeCells('B20:C20');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B20', substr($apply->add_time,0,10));
        $objPHPExcel->getActiveSheet()->getStyle('B20')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCells('E20:F20');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E20', $user->departmentFullRoute);
        $objPHPExcel->getActiveSheet()->mergeCells('H20:I20');
        /*$record = ApplyRecord::find()->where(['apply_id'=>$apply->id,'step'=>0])->one();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', $record->attacment!='[]'?'有':'无');*/


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A21', '报销人员');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D21', '职务');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G21', '报销公司');

        $objPHPExcel->getActiveSheet()->mergeCells('B21:C21');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B21', $user->username);
        $objPHPExcel->getActiveSheet()->mergeCells('E21:F21');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E21', $user->position->name);
        $objPHPExcel->getActiveSheet()->mergeCells('H21:I21');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H21', $company);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A22', '出差日期');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D22', '返回日期');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G22', '出差天数');

        $objPHPExcel->getActiveSheet()->mergeCells('B22:C22');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B22', $options['day']['value']);
        $objPHPExcel->getActiveSheet()->getStyle('B22')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCells('E22:F22');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E22', $options['day2']['value']);
        $objPHPExcel->getActiveSheet()->getStyle('E22')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCells('H22:I22');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H22', $options['daynum']['value']);
        $objPHPExcel->getActiveSheet()->getStyle('H22')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A23', '同行人员');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D23', '报销事由');

        $objPHPExcel->getActiveSheet()->mergeCells('B23:C23');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B23', $options['people']['value']);
        $objPHPExcel->getActiveSheet()->getStyle('B23')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCells('E23:I23');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E23', $options['reason']['value']);


        $objPHPExcel->getActiveSheet()->mergeCells('B24:D24');
        $objPHPExcel->getActiveSheet()->mergeCells('F24:I24');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A24', '序号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B24', '项目');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E24', '凭证数量');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F24', '合计金额');

        $objPHPExcel->getActiveSheet()->getStyle('A24')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B24')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E24')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F24')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $i = 1;
        $l = 25;
        foreach($project as $p){
            $objPHPExcel->getActiveSheet()->mergeCells('B'.$l.':D'.$l);
            $objPHPExcel->getActiveSheet()->mergeCells('F'.$l.':I'.$l);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, $i);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$l, $p['item']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, $p['pin']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$l, $p['price']);

            $objPHPExcel->getActiveSheet()->getStyle('A'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            foreach($arr as $a){
                $objSheet->getStyle($a.$l)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            }
            $objSheet->getStyle('A'.$l)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('D'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('E'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('I'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);


            $l++;
            $i++;
        }


        $objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':D'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '合计金额');
        $objPHPExcel->getActiveSheet()->getStyle('A'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, $pinAll);
        $objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->mergeCells('F'.$l.':I'.$l);

        //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$l, $options['all']['value']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$l, $priceAll);
        $objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);



        foreach($arr as $a){
            $objSheet->getStyle($a.$l)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A'.$l)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('E'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);



        $l++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '借款抵扣');
        $objPHPExcel->getActiveSheet()->mergeCells('B'.$l.':D'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, '结算金额');
        $objPHPExcel->getActiveSheet()->mergeCells('F'.$l.':I'.$l);

        foreach($arr as $a){
            $objSheet->getStyle($a.$l)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A'.$l)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('E'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);



        $l++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '收款方式');
        $objPHPExcel->getActiveSheet()->mergeCells('B'.$l.':D'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, '大写');
        $objPHPExcel->getActiveSheet()->mergeCells('F'.$l.':I'.$l);

        foreach($arr as $a){
            $objSheet->getStyle($a.$l)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A'.$l)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('E'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        $l++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '开户银行');
        $objPHPExcel->getActiveSheet()->mergeCells('B'.$l.':D'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, '账号');
        $objPHPExcel->getActiveSheet()->mergeCells('F'.$l.':I'.$l);

        foreach($arr as $a){
            $objSheet->getStyle($a.$l)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A'.$l)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('E'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);


        $l++;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':B'.$l);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '审批流程');
        $objPHPExcel->getActiveSheet()->mergeCells('C'.$l.':D'.$l);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$l, '签名');
        $objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':F'.$l);
        $objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, '批示');
        $objPHPExcel->getActiveSheet()->mergeCells('G'.$l.':H'.$l);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$l, '批注');
        $objPHPExcel->getActiveSheet()->getStyle('I'.$l)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$l, '时间');

        foreach($arr as $a){
            $objSheet->getStyle($a.$l)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A'.$l)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('B'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('H'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);


        $l++;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':B'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '审批流程');
        $objPHPExcel->getActiveSheet()->mergeCells('C'.$l.':D'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$l, '签名');
        $objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':F'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, '批示');
        $objPHPExcel->getActiveSheet()->mergeCells('G'.$l.':H'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$l, '批注');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$l, '时间');

        foreach($arr as $a){
            $objSheet->getStyle($a.$l)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A'.$l)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('B'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('H'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        $l++;
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':B'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$l, '审批流程');
        $objPHPExcel->getActiveSheet()->mergeCells('C'.$l.':D'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$l, '签名');
        $objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':F'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$l, '批示');
        $objPHPExcel->getActiveSheet()->mergeCells('G'.$l.':H'.$l);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$l, '批注');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$l, '时间');

        foreach($arr as $a){
            $objSheet->getStyle($a.$l)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        $objSheet->getStyle('A'.$l)->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('B'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('D'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('H'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I'.$l)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);



        /*


// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);*/


// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        $filename = 'songtangoa-'.date('Y-m-dTH:i:s');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }


    public function export14($apply){
        $user = User::find()->where(['id'=>$apply->user_id])->one();

        $formContent = ApplyFormContent::find()->where(['apply_id'=>$apply->id])->all();
        $options = [];
        foreach($formContent as $item){
            $options[$item->item_key] = FormItem::jsonDecodeValue($item->item_value);
        }

        $fentan = $options['fentan']['input_options'][$options['fentan']['value']];
        $reason = $options['reason']['input_options'][$options['reason']['value']];
        $shoukuantype = $options['shoukuantype']['input_options'][$options['shoukuantype']['value']];




        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '对公付款');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '申请日期');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', '费用部门');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', '是否分摊');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', substr($apply->add_time,0,10));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', $user->departmentFullRoute);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', $fentan);
        /*$record = ApplyRecord::find()->where(['apply_id'=>$apply->id,'step'=>0])->one();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', $record->attacment!='[]'?'有':'无');*/


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', '申请人员');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', '职务');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '付款公司');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', $user->username);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', $user->position->name);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', $options['company']['value']);



        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', '付款日期');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', '付款事由');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', $options['payday']['value']);
        $objPHPExcel->getActiveSheet()->mergeCells('D4:F4');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', $reason);


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', '发票状态');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', '预批申请');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', '合同');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', $options['invoicestatus']['value']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', $options['yupi']['value']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F5', $options['hetong']['value']);


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', '申请金额');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C6', '借款单号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E6', '借款抵扣');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B6', $options['price']['value'].' 元');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D6', $options['danhao']['value']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F6', $options['dikou']['value']);


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7', '收款单位');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D7', '付款金额');

        $objPHPExcel->getActiveSheet()->mergeCells('B7:C7');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B7', $options['shoukuancompany']['value']);
        $objPHPExcel->getActiveSheet()->mergeCells('E7:F7');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', $options['payprice']['value'].' 元');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A8', '收款方式');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D8', '大写');

        $objPHPExcel->getActiveSheet()->mergeCells('B8:C8');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B8', $shoukuantype);
        $objPHPExcel->getActiveSheet()->mergeCells('E8:F8');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E8', $options['bigprice']['value'].' 元');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A9', '开户银行');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D9', '账号');

        $objPHPExcel->getActiveSheet()->mergeCells('B9:C9');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B9', $options['bank']['value']);
        $objPHPExcel->getActiveSheet()->mergeCells('E9:F9');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E9', $options['account']['value']);




        /*


// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);*/


// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        $filename = 'songtangoa-'.date('Y-m-dTH:i:s');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
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
            $applyFromContent = ApplyFormContent::find()->where(['apply_id'=>$apply->id])->orderBy('ord asc')->all();
            if(!empty($applyFromContent)){
                $html.= '<section id="apply-form-content" class="task-form-section">';
                $html.='<div>表单详情</div>';
                $html.= FormItem::getHtmlByFormItem($applyFromContent);
                /*
                foreach($applyFromContent as $afc){
                    $valueArr = FormItem::jsonDecodeValue($afc->item_value,$afc->item_key,true);
                    $html.= '<span class="form-content-item">';
                    if($valueArr['label_width']>0) {
                        $html .= '<span class="form-content-label" style="width:' . $valueArr['label_width'] . 'px;">' . $valueArr['label'] . '</span>';
                    }
                    $html .= '<span class="form-content-input" style="width:'.$valueArr['input_width'].'px;">'.$valueArr['itemContent'].'</span>';

                    $html .='</span>';
                }*/
                $html.= '</section>';
            }
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
            $records = ApplyRecord::find()->where(['apply_id'=>$id])->orderBy('step asc')->all();
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
                foreach($recordsDone as $r){
                    if($r->step==0) continue;
                    $htmlOne = '<li>';
                    $htmlOne.= '<div>步骤'.$r->step.'</div>';
                    $htmlOne.= '<div>标题：<b>'.$r->title.'</b>  操作类型：<b>'.Flow::typeName($r->type).'</b></div>';
                    $flow_user = CommonFunc::getByCache(UserIdentity::className(),'findIdentityOne',[$r->user_id],'ucenter:user/identity');
                    $username = $flow_user?$flow_user->name:'N/A';
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
            if($apply->status==1 && !empty($recordsTodo)){
                foreach($recordsTodo as $r) {
                    $htmlOne = '<li class="not-do">';
                    $htmlOne .= '<div>步骤' . $r->step . ' 还未操作</div>';
                    $htmlOne .= '<div>标题：<b>' . $r->title . '</b>  操作类型：<b>' .Flow::typeName($r->type). '</b></div>';
                    $flow_user = CommonFunc::getByCache(UserIdentity::className(),'findIdentityOne',[$r->user_id],'ucenter:user/identity');
                    $username = $flow_user?$flow_user->name:'N/A';
                    $htmlOne .= '<div>操作人：<b>' . $username . '</b> </div>';
                    $htmlOne .= '</li>';
                    $html2 .= $htmlOne;
                }
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
                //$params['flowNotDo'] = $flowNotDo;
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
                $curRecord = ApplyRecord::find()->where(['apply_id'=>$apply->id,'step'=>$apply->flow_step])->one();
                if($curRecord ){

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
