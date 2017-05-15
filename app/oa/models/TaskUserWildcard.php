<?php

namespace oa\models;

//oa 任务发起人 对应匹配表 根据 地区  行业 公司 部门 职位

use ucenter\models\Department;
use ucenter\models\Position;
use ucenter\models\User;
use Yii;

class TaskUserWildcard extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }

    public function attributeLabels(){
        return [
            'task_id' => '任务ID',
            //'user_id' => '用户ID',
        ];
    }

    public function rules()
    {
        return [
            [['task_id', 'district_id','industry_id','company_id','department_id','position_id'], 'integer'],
        ];
    }


    public function getUsers(){
        $query = User::find();
        if($this->district_id>10000){
            $query->andWhere(['district_id'=>$this->district_id]);
        }
        if($this->industry_id>10000){
            $query->andWhere(['industry_id'=>$this->industry_id]);
        }
        if($this->company_id>10000){
            $query->andWhere(['company_id'=>$this->company_id]);
        }

        $depart = Department::find()->where(['id'=>$this->department_id])->andWhere(['>','id',10000])->one();
        if($depart){
            $departIds[] = $this->department_id;
            $departChildren = Department::getChildren($this->department_id);
            if(!empty($departChildren)){
                foreach($departChildren as $departChild){
                    $departIds[] = $departChild->id;
                }
            }
            $query->andWhere(['department_id'=>$departIds]);
        }

        $pos = Position::find()->where(['id'=>$this->position_id])->andWhere(['>','id',10000])->one();
        if($pos){
            $posIds[] = $this->position_id;
            $posChildren = Position::getChildren($this->position_id);
            if(!empty($posChildren)){
                foreach($posChildren as $posChild){
                    $posIds[] = $posChild->id;
                }
            }
            $query->andWhere(['position_id'=>$posIds]);
        }

        return $query->all();

        //return $this->hasMany(UserGroupUser::className(), array('group_id' =>'id'));
    }

/*

CREATE TABLE `task_user_wildcard` (
`id` int(5) NOT NULL AUTO_INCREMENT,
`task_id` int(11) NOT NULL,
`district_id` int(11) NOT NULL,
`industry_id` int(11) NOT NULL,
`company_id` int(11) NOT NULL,
`department_id` int(11) NOT NULL,
`position_id` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

*/

}
