<?php

namespace oa\models;

//oa 模板下的表单

use Yii;

class Form extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }

    public function attributeLabels(){
        return [
            'title' => '标题',
            'status' =>'状态'
        ];
    }

    public function rules()
    {
        return [
            [['title'],'required'],
            [['status'], 'integer']
        ];
    }

    public static function getCategory($form_id){
        $formCategory = FormCategory::find()->where(['form_id'=>$form_id])->all();
        $cids = [];
        foreach($formCategory as $fc){
            $cids[] = $fc->category_id;
        }

        $category = TaskCategory::find()->where(['id'=>$cids])->orderBy('type asc')->all();
        $list = [];
        foreach($category as $c){
            $list[$c->type][$c->id] = $c->name;
        }
        $typeList = TaskCategory::getTypeList();
        $return = '';
        foreach($list as $k=>$types){
            $return .= '<span style="color:#999;">'.$typeList[$k].'</span> : '.implode(' , ',$types).'<br/>';
        }




        return $return ;


    }


    public static function getDropdownList(){
        $list = [];

        $form = Form::find()->where(['status'=>1])->all();
        foreach($form as $f){
            $list[$f->id] = $f->title;
        }
        return $list;
    }

    /*public function getUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getTask(){
        return $this->hasOne(Task::className(), array('id' => 'task_id'));
    }*/

}
