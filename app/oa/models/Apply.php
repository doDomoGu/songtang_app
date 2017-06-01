<?php

namespace oa\models;

/*ALTER TABLE `apply` ADD `flow_user` VARCHAR(100) NOT NULL AFTER `flow_step`;*/

//oa 申请表
use ucenter\models\User;
use Yii;
class Apply extends \yii\db\ActiveRecord
{

    ////ALTER TABLE `apply` ADD `task_category` INT NOT NULL AFTER `task_id`;


    public static function getDb(){
        return Yii::$app->db_oa;
    }
    const STATUS_NORMAL     = 1; //正常流程中
    const STATUS_DELETE     = 0; //删除 撤销
    const STATUS_SUCCESS    = 2; //成功 完成
    const STATUS_FAILURE    = 3; //失败 发生情况：1.执行类型的流程结果是 失败
    const STATUS_BEATBACK   = 4; //打回 发生情况：1.审批类型的流程结果是 不通过
    const STATUS_APPLYDELETE = 5; //申请撤销

    const STATUS_NORMAL_CN  = '执行中';
    const STATUS_DELETE_CN  = '撤销';
    const STATUS_SUCCESS_CN  = '已完成';//'已完成(执行成功)';
    const STATUS_FAILURE_CN  = '失败'; //'已完成(执行失败)';
    const STATUS_BEATBACK_CN  = '打回';
    const STATUS_APPLYDELETE_CN  = '申请撤销';

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'title' => '申请标题',
            'user_id' => '发起人ID',
            'task_id' => '对应任务表Id',
            'flow_step' => '流程执行到第几步',
            'message' => '申请人填写的备注/内容',
            'add_time' => '开始时间',
            'edit_time' => '编辑时间',
            'status' => '状态',

        ];
    }

    public function rules()
    {
        return [
            [['title','user_id','task_id','flow_step','task_category'], 'required'],
            [['user_id','task_id','flow_step','status','task_category'], 'integer'],
            [['add_time','edit_time','message','flow_user'],'safe']
        ];
    }

    public function getApplyUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }


    public function getFlow(){
        return $this->hasOne(Flow::className(), array('step' => 'flow_step','task_id'=>'task_id'));
    }

    public function getTask(){
        return $this->hasOne(Task::className(), array('id' => 'task_id'));
    }


    public static function getStatusCn($status){
        switch($status){
            case self::STATUS_NORMAL:
                $return = self::STATUS_NORMAL_CN;
                break;
            case self::STATUS_DELETE:
                $return = self::STATUS_DELETE_CN;
                break;
            case self::STATUS_SUCCESS:
                $return = self::STATUS_SUCCESS_CN;
                break;
            case self::STATUS_FAILURE:
                $return = self::STATUS_FAILURE_CN;
                break;
            case self::STATUS_BEATBACK:
                $return = self::STATUS_BEATBACK_CN;
                break;
            case self::STATUS_APPLYDELETE:
                $return = self::STATUS_APPLYDELETE_CN;
                break;
            default:
                $return = 'N/A';
        }

        return $return;
    }

    //
    public static function getMyList($getCount=false){
        $query = self::find()->where(['user_id'=>Yii::$app->user->id]);
        if($getCount)
            $return = $query->count();
        else
            $return = $query->orderBy('add_time desc')->all();
        return $return;
    }

    public static function getLasttime($apply_id){
        $apply = self::find()->where(['id'=>$apply_id])->one();
        if($apply){
            if($apply->flow_step>1){
                return $apply->edit_time;
            }else{
                return $apply->add_time;
            }
        }else{
            return null;
        }
    }

    //根据
    public static function isFlowUser($user_id,$flow,$apply){
        $return = false;
        if($flow){
            if($flow->user_id>0){
                if($flow->user_id == $user_id){
                    $return = true;
                }
            }else{
                //如果user_id = 0 则是由发起人选择的  在apply的flow_user字段中
                $arr =  self::flowUserStr2Arr($apply->flow_user);
                if(isset($arr[$apply->flow_step]) && $arr[$apply->flow_step] == $user_id){
                    $return = true;
                }
            }
        }

        return $return;
    }

    //待办事项:   当前流程操作人是"我"，且状态为“执行中”（这个是必然的，其他状态没有操作人了，都是已经完成的申请，不是成功就是失败）
    public static function getTodoList($getCount=false){
        $list = [];
        $user_id = Yii::$app->user->id;
        // 1.搜索执行中的申请
        $applyList = Apply::find()->where(['status'=>self::STATUS_NORMAL])->all();

        foreach($applyList as $apply){
            // 2.根据申请对应的任务表  和  步骤 ，判断操作人是不是自己
            $flow = Flow::find()->where(['task_id'=>$apply->task_id,'step'=>$apply->flow_step])->one();

            if(self::isFlowUser($user_id,$flow,$apply))
                $list[] = $apply;
        }

        if($getCount){
            $return = count($list);
        }else{
            $return = $list;
        }

        return $return;
    }

    //办结事项   流程(不包含发起人）中操作人是"我"，且状态为 “已完成”
    public static function getDoneList($getCount=false){
        //$flow = Flow::find()->where(['user_id'=>Yii::$app->user->id])->groupBy('task_id')->orderBy('step desc')->select(['task_id','step'])->all();


        $records = ApplyRecord::find()->where(['user_id'=>Yii::$app->user->id])->all();
        $list = [];
        if(!empty($records)){
            foreach($records as $r){
                $applyList = Apply::find()->where(['id'=>$r->apply_id,'status'=>self::STATUS_SUCCESS])->all();
                if(!empty($applyList)){
                    foreach($applyList as $l){
                        $list[$l->id] = $l;
                    }
                }
            }
        }
        if($getCount){
            $return = count($list);
        }else{
            $return = $list;
        }
        return $return;
    }

    public static function getFinishList($getCount=false){
        $flow = Flow::find()->where(['user_id'=>Yii::$app->user->id])->groupBy('task_id')->select(['task_id'])->all();
        $list = [];
        if(!empty($flow)){
            foreach($flow as $f){
                $applyList = Apply::find()->where(['task_id'=>$f->task_id,'status'=>self::STATUS_SUCCESS])->all();
                if(!empty($applyList))
                    $list = array_merge($list,$applyList);
            }
        }
        if($getCount){
            $return = count($list);
        }else{
            $return = $list;
        }
        return $return;
    }

    public static function getRelatedList($getCount=false){
        $flow = Flow::find()->where(['user_id'=>Yii::$app->user->id])->groupBy('task_id')->select('task_id')->all();
        if(!empty($flow)){
            $taskIds = [];
            foreach($flow as $f){
                $taskIds[] = $f->task_id;
            }

            $todoList = self::getTodoList();
            $doneList = self::getDoneList();
            $finishList = self::getFinishList();
            $notInIds = [];
            foreach($todoList as $l){
                $notInIds[] = $l->id;
            }
            foreach($doneList as $l){
                $notInIds[] = $l->id;
            }
            foreach($finishList as $l){
                $notInIds[] = $l->id;
            }

            $query = Apply::find()->where(['task_id'=>$taskIds])->andWhere(['not in','id',$notInIds])->orderBy('add_time desc');
            if($getCount)
                $return = $query->count();
            else
                $return = $query->all();
        }else{
            if($getCount)
                $return = 0;
            else
                $return = [];
        }
        return $return;
    }


    //将apply的flow_user字段分解成 流程步骤数step => 操作人user_id 的数组
    public static function flowUserStr2Arr($str){
        $arr = [];
        $temp = explode('|',$str);
        foreach($temp as $t){
            $temp2 = explode(':',$t);
            $arr[$temp2[0]] = $temp2[1];
        }
        return $arr;
    }

    public static function flowUserArr2Str($arr){
        $temp = [];
        foreach($arr as $k => $f){
            $temp[] = $k.':'.$f;
        }

        return implode('|',$temp);
    }

    //apply = 指定的申请
    //flow  = 指定的步骤
    //获取对应的操作人
    public static function getOperationUser($apply,$flow){
        if($flow->user_id>0){
            return $flow->user->name;
        }else{
            $temp = self::flowUserStr2Arr($apply->flow_user);
            if($temp){
                if(isset($temp[$flow->step])){
                    $user = User::find()->where(['id'=>$temp[$flow->step]])->one();
                    if($user){
                        return '*'.$user->name;
                    }else{
                        return 'N/A #33';
                    }
                }else{
                    return 'N/A #22';
                }
            }else{
                return 'N/A #11';
            }
        }
    }
}
