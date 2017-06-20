<?php

namespace oa\modules\admin\controllers;

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
use oa\modules\admin\components\AdminFunc;
use ucenter\models\Company;
use ucenter\models\District;
use ucenter\models\Industry;
use ucenter\models\Position;
use ucenter\models\User;
use Yii;
use yii\data\Pagination;
use yii\web\Response;
/**
 *  任务流程管理
 *  task flow
 */
class TaskController extends BaseController
{
    public function actionCategory()
    {
        $list = TaskCategory::find()->all();


        $params['list'] = $list;
        return $this->render('category',$params);
    }

    public function actionForm()
    {
        $query = Form::find();

        $count = $query->count();
        $pageSize = 10;
        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => $pageSize,'pageSizeParam'=>false]);
        $list = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        $params['list'] = $list;
        $params['pages'] = $pages;
        $params['categoryList'] = TaskCategory::getDropdownList();
        return $this->render('form',$params);
    }

    public function actionIndex()
    {
        $aid = Yii::$app->request->get('aid',false);
        $bid = Yii::$app->request->get('bid',false);
        $query = Task::find();

        $count = $query->count();
        $pageSize = 10;
        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => $pageSize,'pageSizeParam'=>false]);
        $list = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->all();

        $params['list'] = $list;
        $params['pages'] = $pages;
        /*$params['districtArr'] = District::getNameArr();
        $params['industryArr'] = Industry::getNameArr();
        $params['companyArr'] = Company::getNameArr();*/
        $params['pArr'] = Position::getNameArr();
        $params['industryArr2'] = District::getIndustryRelationsArr($aid);

        $params['aid'] = $aid;
        $params['bid'] = $bid;
        $params['taskCategoryList'] = TaskCategory::getDropdownList();
        $params['formList'] = Form::getDropdownList();
        return $this->render('index',$params);
    }


    public function actionGet(){
        $errormsg = '';
        $result = false;
        $info = [];
        if(Yii::$app->request->isAjax){
            $task_id = Yii::$app->request->post('id',0);
            $task = Task::find()->where(['id'=>$task_id])->one();
            if($task){
                $info['title'] = $task->title;
                $category = TaskCategoryId::find()->where(['task_id'=>$task_id])->all();
                $cateArr = [];
                foreach($category as $c){
                    $cateArr[] = $c->category_id;
                }
                $info['category_ids'] = implode(',',$cateArr);
                $result = true;
            }else{
                $errormsg = '模板不存在';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'info'=>$info];
    }


    public function actionFormGet(){
        $errormsg = '';
        $result = false;
        $info = [];
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id',0);
            $form = Form::find()->where(['id'=>$id])->one();
            if($form){
                $info['title'] = $form->title;
                $category = FormCategory::find()->where(['form_id'=>$id])->all();
                $cateArr = [];
                foreach($category as $c){
                    $cateArr[] = $c->category_id;
                }
                $info['category_ids'] = implode(',',$cateArr);
                $result = true;
            }else{
                $errormsg = '表单不存在';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'info'=>$info];
    }

    public function actionTaskFormGet(){
        $errormsg = '';
        $result = false;
        $info = [];
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id',0);
            $task = Task::find()->where(['id'=>$id])->one();
            if($task){
                $info['title'] = $task->title;
                $form = TaskForm::find()->where(['task_id'=>$id])->all();
                $formArr = [];
                foreach($form as $f){
                    $formArr[] = $f->form_id;
                }
                $info['form_ids'] = implode(',',$formArr);
                $result = true;
            }else{
                $errormsg = '模板不存在';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg,'info'=>$info];
    }

    public function actionCreate(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $title = trim(Yii::$app->request->post('title',false));
            $category_id = Yii::$app->request->post('category_id','');
            $district_id = intval(Yii::$app->request->post('district_id',10000));
            $industry_id = intval(Yii::$app->request->post('industry_id',10000));
            $company_id = intval(Yii::$app->request->post('company_id',10000));
            $department_id = intval(Yii::$app->request->post('department_id',10000));
            //AREA BUSINESS DEPARTMENT  TODO
            if($title==''){
                $errormsg = '标题不能为空！';
            }else{
                $exist = Task::find()->where(['title'=>$title])->one();
                if($exist){
                    $errormsg = '标题已存在!';
                }else{
                    if($category_id==''){
                        $errormsg = '请勾选至少一个模板分类！';
                    }else{
                        $task = new Task();
                        $task->title = $title;
                        $task->district_id = $district_id;
                        $task->industry_id = $industry_id;
                        $task->company_id = $company_id;
                        $task->department_id = $department_id;
                        $task->ord = 0;
                        $task->status = 1;
                        if($task->save()){
                            $categoryIds = explode(',',$category_id);

                            foreach($categoryIds as $cate_id){
                                $taskCategory = TaskCategory::find()->where(['id'=>$cate_id])->one();
                                if($taskCategory){
                                    $taskCategoryId = new TaskCategoryId();
                                    $taskCategoryId->task_id = $task->id;
                                    $taskCategoryId->category_id = $cate_id;
                                    $taskCategoryId->save();
                                }
                            }
                            /*$user = User::find()->all();
                            foreach($user as $u){
                                $taskUser = new TaskApplyUser();
                                $taskUser->task_id = $task->id;
                                $taskUser->user_id = $u->id;
                                $taskUser->save();
                            }*/


                            Yii::$app->getSession()->setFlash('success','新增模板【'.$task->title.'】成功！');
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


    public function actionFormCreate(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $title = trim(Yii::$app->request->post('title',false));
            $category_id = Yii::$app->request->post('category_id','');
            if($title==''){
                $errormsg = '标题不能为空！';
            }else{
                $exist = Form::find()->where(['title'=>$title])->one();
                if($exist){
                    $errormsg = '标题已存在!';
                }else{
                    if($category_id==''){
                        $errormsg = '请勾选至少一个分类！';
                    }else{
                        $form = new Form();
                        $form->title = $title;
                        $form->status = 1;
                        if($form->save()){
                            $categoryIds = explode(',',$category_id);

                            foreach($categoryIds as $cate_id){
                                $taskCategory = TaskCategory::find()->where(['id'=>$cate_id])->one();
                                if($taskCategory){
                                    $formCategory = new FormCategory();
                                    $formCategory->form_id = $form->id;
                                    $formCategory->category_id = $cate_id;
                                    $formCategory->save();
                                }
                            }
                            /*$user = User::find()->all();
                            foreach($user as $u){
                                $taskUser = new TaskApplyUser();
                                $taskUser->task_id = $task->id;
                                $taskUser->user_id = $u->id;
                                $taskUser->save();
                            }*/


                            Yii::$app->getSession()->setFlash('success','新增表单【'.$form->title.'】成功！');
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

    public function actionEdit(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $task_id = Yii::$app->request->post('task_id',0);
            $title = trim(Yii::$app->request->post('title',false));
            $category_id = Yii::$app->request->post('category_id','');
            $task = Task::find()->where(['id'=>$task_id])->one();
            if($task){
                if($title==''){
                    $errormsg = '标题不能为空！';
                }else {
                    $exist = Task::find()->where(['title' => $title])->andWhere(['<>', 'id', $task_id])->one();
                    if ($exist) {
                        $errormsg = '标题已存在!';
                    } else {
                        if ($category_id == '') {
                            $errormsg = '请勾选至少一个模板分类！';
                        } else {
                            $task->title = $title;
                            $task->save();

                            TaskCategoryId::deleteAll(['task_id' => $task_id]);
                            $categoryIds = explode(',', $category_id);

                            foreach ($categoryIds as $cate_id) {
                                $taskCategory = TaskCategory::find()->where(['id' => $cate_id])->one();
                                if ($taskCategory) {
                                    $taskCategoryId = new TaskCategoryId();
                                    $taskCategoryId->task_id = $task->id;
                                    $taskCategoryId->category_id = $cate_id;
                                    $taskCategoryId->save();
                                }
                            }


                            Yii::$app->getSession()->setFlash('success', '编辑模板【' . $task->title . '】成功！');
                            $result = true;
                        }
                    }
                }
            }else{
                $errormsg = '模板错误！';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

    public function actionFormEdit(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('form_id',0);
            $title = trim(Yii::$app->request->post('title',false));
            $category_id = Yii::$app->request->post('category_id','');
            $form = Form::find()->where(['id'=>$id])->one();
            if($form){
                if($title==''){
                    $errormsg = '标题不能为空！';
                }else {
                    $exist = Form::find()->where(['title' => $title])->andWhere(['<>', 'id', $id])->one();
                    if ($exist) {
                        $errormsg = '标题已存在!';
                    } else {
                        if ($category_id == '') {
                            $errormsg = '请勾选至少一个分类！';
                        } else {
                            $form->title = $title;
                            $form->save();

                            FormCategory::deleteAll(['form_id' => $id]);
                            $categoryIds = explode(',', $category_id);

                            foreach ($categoryIds as $cate_id) {
                                $taskCategory = TaskCategory::find()->where(['id' => $cate_id])->one();
                                if ($taskCategory) {
                                    $formCategory = new FormCategory();
                                    $formCategory->form_id = $form->id;
                                    $formCategory->category_id = $cate_id;
                                    $formCategory->save();
                                }
                            }


                            Yii::$app->getSession()->setFlash('success', '编辑表单【' . $form->title . '】成功！');
                            $result = true;
                        }
                    }
                }
            }else{
                $errormsg = '模板错误！';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

    public function actionTaskFormEdit(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $task_id = Yii::$app->request->post('task_id',0);
            $form_id = Yii::$app->request->post('form_id','');
            $task = Task::find()->where(['id'=>$task_id])->one();
            if($task){
                TaskForm::deleteAll(['task_id' => $task_id]);
                $formIds = explode(',', $form_id);

                foreach ($formIds as $f_id) {
                    $form = Form::find()->where(['id' => $f_id])->one();
                    if ($form) {
                        $taskForm = new TaskForm();
                        $taskForm->task_id = $task->id;
                        $taskForm->form_id = $f_id;
                        $taskForm->save();
                    }
                }


                Yii::$app->getSession()->setFlash('success', '编辑模板【' . $task->title . '】成功！');
                $result = true;

            }else{
                $errormsg = '模板错误！';
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
        $task = Task::find()->where(['id'=>$tid])->one();
        if($task){
            $flow = Flow::find()->where(['task_id'=>$tid])->orderBy('step asc')->all();
            $params['list'] = $flow;
            $params['task'] = $task;
            return $this->render('flow',$params);
        }else{
            Yii::$app->getSession()->setFlash('error','流程设置对应的任务id不存在!');
            return $this->redirect(AdminFunc::adminUrl('task'));
        }
    }

    public function actionFormItem(){
        $id = Yii::$app->request->get('id',false);
        $form = Form::find()->where(['id'=>$id])->one();
        if($form){
            $formItem = FormItem::find()->where(['form_id'=>$id])->orderBy('ord asc')->all();
            $params['list'] = $formItem;
            $params['form'] = $form;
            return $this->render('form_item',$params);
        }else{
            Yii::$app->getSession()->setFlash('error','表单ID错误!');
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
            $enable_transfer = intval(Yii::$app->request->post('enable_transfer',0));
            if($title==''){
                $errormsg = '名称不能为空！';
            }else{
                $exist = Task::find()->where(['id'=>$tid])->one();
                if(!$exist){
                    $errormsg = '对应的任务ID不存在！';
                }else{
                    /*$existUser = User::find()->where(['id'=>$user_id])->one();
                    if(!$existUser){
                        $errormsg = '所选职员ID不存在！';
                    }else{*/
                        $last = Flow::find()->where(['task_id'=>$tid])->orderBy('step desc')->one();
                        $flow = new Flow();
                        $flow->title = $title;
                        $flow->task_id = $tid;
                        $flow->user_id = $user_id;
                        $flow->type = $type;
                        $flow->enable_transfer = $enable_transfer;
                        $flow->step = isset($last)?$last->step+1:1;
                        $flow->status = 1;
                        if($flow->save()){
                            Yii::$app->getSession()->setFlash('success','新增流程【'.$flow->title.'】成功！');
                            $result = true;
                        }else{
                            $errormsg = '保存失败，刷新页面重试!';
                        }
                    /*}*/
                }
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

    public function actionFormItemCreate(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $form_id =intval(Yii::$app->request->post('form_id',0));
            $key = trim(Yii::$app->request->post('key',false));
            $label = trim(Yii::$app->request->post('label',false));
            $label_width = trim(Yii::$app->request->post('label_width',false));
            $input_width = trim(Yii::$app->request->post('input_width',false));
            $input_type = intval(Yii::$app->request->post('input_type',0));
            $input_options = trim(Yii::$app->request->post('input_options',''));
            if($key==false || $label==false){
                $errormsg = '名称不能为空！';
            }else{
                $form = Form::find()->where(['id'=>$form_id])->one();
                if(!$form){
                    $errormsg = '对应的表单ID不存在！';
                }else{
                    $existItem = FormItem::find()->where(['form_id'=>$form_id,'item_key'=>$key])->one();
                    if(!$existItem){
                        $last = FormItem::find()->where(['form_id'=>$form_id])->orderBy('ord desc')->one();
                        $formItem = new FormItem();
                        $formItem->form_id = $form_id;
                        $formItem->item_key = $key;
                        $valueArr = [
                            'label'=>$label,
                            'label_width'=>$label_width,
                            'input_width'=>$input_width,
                            'input_type'=>$input_type,
                            'input_options'=>explode(',',$input_options)
                        ];

                        $formItem->item_value = json_encode($valueArr);;
                        $formItem->ord = $last?intval($last->ord) + 1 : 1;
                        $formItem->status = 1;
                        if($formItem->save()){
                            Yii::$app->getSession()->setFlash('success','新增选项【'.$form->title.'】成功！');
                            $result = true;
                        }else{
                            $errormsg = '保存失败，刷新页面重试!';
                        }
                    }else{
                        $errormsg = 'Key名重复!';
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

    public function actionFlowEdit(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $title = trim(Yii::$app->request->post('title',false));
            $type = intval(Yii::$app->request->post('type',0));
            $user_id = intval(Yii::$app->request->post('user_id',0));
            $tid = intval(Yii::$app->request->post('tid',0));
            $flow_id = intval(Yii::$app->request->post('flow_id',0));
            $enable_transfer = intval(Yii::$app->request->post('enable_transfer',0));

            if($title==''){
                $errormsg = '名称不能为空！';
            }else{
                $exist = Task::find()->where(['id'=>$tid])->one();
                if(!$exist){
                    $errormsg = '对应的任务ID不存在！';
                }else{
                    $flow = Flow::find()->where(['task_id'=>$tid,'id'=>$flow_id])->one();
                    if(!$flow){
                        $errormsg = '流程不存在！';
                    }else{
                        $flow->title = $title;
                        $flow->task_id = $tid;
                        $flow->user_id = $user_id;
                        $flow->type = $type;
                        if($flow->save()){
                            Yii::$app->getSession()->setFlash('success','修改流程【'.$flow->title.'】成功！');
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

    public function actionSetComplete(){
        $id = Yii::$app->request->get('id',false);
        $task = Task::find()->where(['id'=>$id])->one();
        if($task){
            $task->set_complete = 1;
            $task->save();
            Yii::$app->getSession()->setFlash('success','模板【'.$task->title.'】启用成功！');
        }else{
            Yii::$app->getSession()->setFlash('error','模板ID不存在！');
        }
        return $this->redirect('/admin/task');

    }

    public function actionSetComplete2(){
        $id = Yii::$app->request->get('id',false);
        $task = Task::find()->where(['id'=>$id])->one();
        if($task){
            $task->set_complete = 0;
            $task->save();
            Yii::$app->getSession()->setFlash('success','模板【'.$task->title.'】暂停使用！');
        }else{
            Yii::$app->getSession()->setFlash('error','任务表ID不存在！');
        }
        return $this->redirect('/admin/task');

    }

    public function actionFormSetComplete(){
        $id = Yii::$app->request->get('id',false);
        $form = Form::find()->where(['id'=>$id])->one();
        if($form){
            $form->set_complete = 1;
            $form->save();
            Yii::$app->getSession()->setFlash('success','表单【'.$form->title.'】启用成功！');
        }else{
            Yii::$app->getSession()->setFlash('error','表单ID不存在！');
        }
        return $this->redirect('/admin/task/form');

    }

    public function actionFormSetComplete2(){
        $id = Yii::$app->request->get('id',false);
        $form = Form::find()->where(['id'=>$id])->one();
        if($form){
            $form->set_complete = 0;
            $form->save();
            Yii::$app->getSession()->setFlash('success','表单【'.$form->title.'】暂停使用！');
        }else{
            Yii::$app->getSession()->setFlash('error','表单ID不存在！');
        }
        return $this->redirect('/admin/task/form');

    }






    public function actionApplyUserDel(){
        $id = Yii::$app->request->get('id',false);
        $tid = Yii::$app->request->get('tid',false);
        $one = TaskUserWildcard::find()->where(['id'=>$id])->one();
        if($one){
            TaskUserWildcard::deleteAll(['id'=>$id]);
            Yii::$app->getSession()->setFlash('success','删除"发起人设置"完成！');
        }else{
            Yii::$app->getSession()->setFlash('error','发起人设置,不存在！');
        }
        return $this->redirect('/admin/task/apply-user?tid='.$tid);

    }


    public function actionApplyUser(){
        $tid = Yii::$app->request->get('tid',false);
        $task = Task::find()->where(['id'=>$tid])->one();
        if($task){
            $list = TaskUserWildcard::find()->where(['task_id'=>$tid])->all();

            //$params['list'] = $applyUser;
            $userList = [];
            foreach($list as $l){
                $result = $l->getUsers();
                foreach($result as $r){
                    $userList[$r->id] = $r;
                }
            }

            /*$params['applyUserList'] = $applyUserList;

            $params['userList'] = User::find()->all();*/

            $params['userList'] = $userList;

            $params['task'] = $task;
            $params['list'] = $list;

            return $this->render('apply_user',$params);
        }else{
            Yii::$app->getSession()->setFlash('error','发起人设置对应的任务id不存在!');
            return $this->redirect(AdminFunc::adminUrl('task'));
        }
    }

    public function actionApplyUser22(){
        $tid = Yii::$app->request->get('tid',false);
        $task = Task::find()->where(['id'=>$tid])->one();
        if($task){
            $applyUser= TaskApplyUser::find()->where(['task_id'=>$tid])->all();

            //$params['list'] = $applyUser;
            $applyUserList = [];
foreach($applyUser as $au){
    $applyUserList[] = $au->user_id;
}
$params['applyUserList'] = $applyUserList;

            $params['userList'] = User::find()->all();

            $params['task'] = $task;
            return $this->render('apply_user',$params);
        }else{
            Yii::$app->getSession()->setFlash('error','发起人设置对应的任务id不存在!');
            return $this->redirect(AdminFunc::adminUrl('task'));
        }
    }


    public function actionApplyUserAdd(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){

            $tid = intval(Yii::$app->request->post('tid',0));
            $district_id = intval(Yii::$app->request->post('district_id',10000));
            $industry_id = intval(Yii::$app->request->post('industry_id',10000));
            $company_id = intval(Yii::$app->request->post('company_id',10000));
            $department_id = intval(Yii::$app->request->post('department_id',10000));
            $position_id = intval(Yii::$app->request->post('position_id',10000));

            $new = new TaskUserWildcard();
            $new->task_id = $tid;
            $new->district_id = $district_id;
            $new->industry_id = $industry_id;
            $new->company_id = $company_id;
            $new->department_id = $department_id;
            $new->position_id = $position_id;
            $new->save();



            Yii::$app->getSession()->setFlash('success','添加发起人成功！');
            $result = true;
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

    public function actionApplyUserEdit(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){

            $id = intval(Yii::$app->request->post('edit_id',0));
            $one = TaskUserWildcard::find()->where(['id'=>$id])->one();
            if($one){
                $district_id = intval(Yii::$app->request->post('district_id',10000));
                $industry_id = intval(Yii::$app->request->post('industry_id',10000));
                $company_id = intval(Yii::$app->request->post('company_id',10000));
                $department_id = intval(Yii::$app->request->post('department_id',10000));
                $position_id = intval(Yii::$app->request->post('position_id',10000));

                $one->district_id = $district_id;
                $one->industry_id = $industry_id;
                $one->company_id = $company_id;
                $one->department_id = $department_id;
                $one->position_id = $position_id;
                $one->save();

                Yii::$app->getSession()->setFlash('success','编辑发起人成功！');
                $result = true;
            }else{
                $errormsg = '设置 没找到!';
            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

    public function actionApplyUserAdd2233(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $user_id = intval(Yii::$app->request->post('user_id',0));
            $tid = intval(Yii::$app->request->post('tid',0));
            $exist = Task::find()->where(['id'=>$tid])->one();
            if(!$exist){
                $errormsg = '对应的任务ID不存在！';
            }else{
                $existUser = User::find()->where(['id'=>$user_id])->one();
                if(!$existUser){
                    $errormsg = '所选职员ID不存在！';
                }else{
                    $existData = TaskApplyUser::find()->where(['task_id'=>$tid,'user_id'=>$user_id])->one();
                    if($existData){
                        $errormsg = '该任务表中此发起人已存在！';
                    }else{
                        $n = new TaskApplyUser();
                        $n->task_id = $tid;
                        $n->user_id = $user_id;
                        if($n->save()){
                            Yii::$app->getSession()->setFlash('success','添加发起人成功！');
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


    public function actionApplyUserAdd2(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $user_id = Yii::$app->request->post('user_id','');
            $tid = intval(Yii::$app->request->post('tid',0));
            $exist = Task::find()->where(['id'=>$tid])->one();
            if(!$exist){
                $errormsg = '对应的任务ID不存在！';
            }else{
                TaskApplyUser::deleteAll(['task_id'=>$tid]);

                $userIds = explode(',',$user_id);
                foreach($userIds as $uid){
                    $n = new TaskApplyUser();
                    $n->task_id = $tid;
                    $n->user_id = $uid;
                    $n->save();
                }

                Yii::$app->getSession()->setFlash('success','修改发起人成功！');
                $result = true;

            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

    public function actionDelete(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $task_id = Yii::$app->request->post('id','');
            $exist = Task::find()->where(['id'=>$task_id])->one();
            if(!$exist){
                $errormsg = '对应的任务ID不存在！';
            }else{
                Task::deleteAll(['id'=>$task_id]);

                Yii::$app->getSession()->setFlash('success','删除任务表（模板）成功！');
                $result = true;

            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }

    public function actionFlowDeleteAll(){
        $errormsg = '';
        $result = false;
        if(Yii::$app->request->isAjax){
            $task_id = Yii::$app->request->post('id','');
            $exist = Task::find()->where(['id'=>$task_id])->one();
            if(!$exist){
                $errormsg = '对应的任务ID不存在！';
            }else{
                FLow::deleteAll(['task_id'=>$task_id]);

                Yii::$app->getSession()->setFlash('success','清空任务表流程成功！');
                $result = true;

            }
        }else{
            $errormsg = '操作错误，请重试!';
        }
        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $response->data=['result'=>$result,'errormsg'=>$errormsg];
    }
}
