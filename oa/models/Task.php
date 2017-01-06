<?php

namespace oa\models;
use ucenter\models\Area;
use ucenter\models\Business;
use Yii;
//oa 任务表 (即预先分配好的oa流程环节)
class Task extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'title' => '任务名称',
            'category_id' => '任务分类',
            'area_id' => '锁定地区',
            'business_id' => '锁定业态',
            'department_id' => '锁定部门',
            'ord' => '排序',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['ord', 'category_id', 'status', 'area_id', 'business_id', 'department_id'], 'integer'],
        ];
    }

    public function getCategory(){
        return $this->hasOne(TaskCategory::className(), array('id' => 'category_id'));
    }



    public function getArea(){
        return $this->hasOne(Area::className(), array('id' => 'area_id'));
    }

    public function getBusiness(){
        return $this->hasOne(Business::className(), array('id' => 'business_id'));
    }

    /*
     * 获取可发起申请的任务列表
     * 参数 area_id
     * 参数 business_id
     * 参数 user_id
     * return  ['id1'=>'title1',...***]
     */
    public static function getList($area_id,$business_id,$user_id=false){
        $return = [];
        //有效的task列表
        $query = Task::find()->where(['status'=>1,'set_complete'=>1]);
        if($area_id>0){
            $query = $query->andWhere(['area_id'=>$area_id]);
        }
        if($business_id>0){
            $query = $query->andWhere(['business_id'=>$business_id]);
        }
        $tasks = $query->all();
        $taskIds = [];
        foreach($tasks as $t){
            $taskIds[] = $t->id;
        }
        if($user_id==false)
            $user_id = Yii::$app->user->id;

        $list = TaskApplyUser::find()->where(['user_id'=>$user_id,'task_id'=>$taskIds])->all();
        foreach($list as $l){
            $return[$l->task_id] = $l->task->category->name.'|'.$l->task->title;
        }
        return $return;
    }


}
