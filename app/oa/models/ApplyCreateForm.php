<?php

namespace oa\models;

use Yii;
use yii\base\Model;

class ApplyCreateForm extends Model
{
    public $id;
    public $title;
    public $task_id;
    public $form_id;
    public $task_category;
    public $message;

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'title' => '申请标题',
            'user_id' => '发起人ID',
            'task_id' => '选择申请任务',
            'form_id' => '选择申请表表单',
            'task_category' => '分类',
            'flow_step' => '流程执行到第几步',
            'message' => '申请备注/内容',
            'add_time' => '开始时间',
            'edit_time' => '编辑时间',
            'status' => '状态',

        ];
    }

    public function rules()
    {
        return [
            [['title','task_id','task_category'], 'required'],
            [['form_id','task_id', 'task_category'], 'integer'],
            [['message'], 'safe'],
            ['task_id','validateTaskStatus'],
            ['form_id','validateFormStatus']
        ];
    }

    public function validateTaskStatus($attribute, $params)
    {
        $task = Task::find()->where(['id'=>$this->$attribute,'status'=>1,'set_complete'=>1])->one();
        if (!$task){
            $this->addError($attribute,'申请表模板状态不正确');
            return false;
        }
        return true;
    }

    public function validateFormStatus($attribute, $params)
    {
        $form = Form::find()->where(['id'=>$this->$attribute,'status'=>1,'set_complete'=>1])->one();
        if (!$form){
            $this->addError($attribute,'申请表表单状态不正确');
            return false;
        }else{
            $taskForm = TaskForm::find()->where(['task_id'=>$this->task_id,'form_id'=>$form->id])->one();
            if(!$taskForm){
                $this->addError($attribute,'申请表模板与表单没有关联关系');
                return false;
            }
        }
        return true;
    }


}
