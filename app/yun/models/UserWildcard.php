<?php

namespace yun\models;
use ucenter\models\Department;
use ucenter\models\Position;
use ucenter\models\User;
use Yii;

class UserWildcard extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_yun;
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
}