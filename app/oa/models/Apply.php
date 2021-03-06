<?php

namespace oa\models;

/*ALTER TABLE `apply` ADD `flow_user` VARCHAR(100) NOT NULL AFTER `flow_step`;*/

//oa 申请表
use common\components\CommonFunc;
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
            'form_id' => '对应表单Id',
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
            [['user_id','task_id','flow_step','status','task_category','form_id'], 'integer'],
            [['add_time','edit_time','message','flow_user','form_number'],'safe']
        ];
    }

    public function getApplyUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }


    public function getFlow(){
        return $this->hasOne(Flow::className(), array('step' => 'flow_step','task_id'=>'task_id'));
    }

    public function getCurRecord(){
        return $this->hasOne(ApplyRecord::className(), array('step' => 'flow_step','apply_id'=>'id'));
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

    public static function getStatusItems(){
        return [
            self::STATUS_NORMAL => self::STATUS_NORMAL_CN,
            self::STATUS_DELETE => self::STATUS_DELETE_CN,
            self::STATUS_SUCCESS => self::STATUS_SUCCESS_CN,
            self::STATUS_FAILURE => self::STATUS_FAILURE_CN,
            self::STATUS_BEATBACK => self::STATUS_BEATBACK_CN,
            self::STATUS_APPLYDELETE => self::STATUS_APPLYDELETE_CN
        ];
    }

    //
    public static function getMyList($search,$getCount=false){
        $query = self::find()->where(['user_id'=>Yii::$app->user->id]);

        foreach($search as $k=>$v){if($k=='title' && $v!=''){
            $query = $query->andWhere(['like','title',$v]);
            }else if($k=='category' && $v!=''){
                $query = $query->andWhere(['task_category'=>$v]);
            }else if($k=='status' && $v!=''){
                $query = $query->andWhere(['status'=>$v]);
            }else if($k=='add_time_start' && $v!=''){
                $query = $query->andWhere(['>=','add_time',$v]);
            }else if($k=='add_time_end' && $v!=''){
                $query = $query->andWhere(['<=','left(add_time,10)',$v]);
            }
        }

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
    public static function getTodoList($search,$getCount=false){
        $list = [];
        $user_id = Yii::$app->user->id;
        // 1.搜索执行中的申请
        $query = Apply::find()->where(['status'=>self::STATUS_NORMAL]);

        foreach($search as $k=>$v){
            if($k=='title' && $v!=''){
                $query = $query->andWhere(['like','title',$v]);
            }else if($k=='category' && $v!=''){
                $query = $query->andWhere(['task_category'=>$v]);
            }else if($k=='status' && $v!=''){
                $query = $query->andWhere(['status'=>$v]);
            }else if($k=='add_time_start' && $v!=''){
                $query = $query->andWhere(['>=','add_time',$v]);
            }else if($k=='add_time_end' && $v!=''){
                $query = $query->andWhere(['<=','left(add_time,10)',$v]);
            }
        }

        $applyList = $query->orderBy('add_time desc')->all();

        foreach($applyList as $apply){
            // 2.根据申请对应的任务表  和  步骤 ，判断操作人是不是自己
            //$flow = Flow::find()->where(['task_id'=>$apply->task_id,'step'=>$apply->flow_step])->one();
            $applyRecord = ApplyRecord::find()->where(['apply_id'=>$apply->id,'step'=>$apply->flow_step])->one();

            if($applyRecord && $applyRecord->user_id==Yii::$app->user->id)
                $list[] = $apply;

            /*if(self::isFlowUser($user_id,$flow,$apply))
                $list[] = $apply;*/
        }

        if($getCount){
            $return = count($list);
        }else{
            $return = $list;
        }

        return $return;
    }

    //办结事项   流程(不包含发起人）中操作人是"我"，且状态为 “已完成”
    public static function getDoneList($search,$getCount=false){
        //$flow = Flow::find()->where(['user_id'=>Yii::$app->user->id])->groupBy('task_id')->orderBy('step desc')->select(['task_id','step'])->all();
        $list = [];
        $user_id = Yii::$app->user->id;
        // 1.搜索完结的申请
        $query = Apply::find()->where(['status'=>self::STATUS_SUCCESS]);

        foreach($search as $k=>$v){
            if($k=='title' && $v!=''){
                $query = $query->andWhere(['like','title',$v]);
            }else if($k=='category' && $v!=''){
                $query = $query->andWhere(['task_category'=>$v]);
            }else if($k=='status' && $v!=''){
                $query = $query->andWhere(['status'=>$v]);
            }else if($k=='add_time_start' && $v!=''){
                $query = $query->andWhere(['>=','add_time',$v]);
            }else if($k=='add_time_end' && $v!=''){
                $query = $query->andWhere(['<=','left(add_time,10)',$v]);
            }
        }


        $applyList = $query->orderBy('add_time desc')->all();

        foreach($applyList as $apply){
            // 2.搜索操作记录中user_id 是不是自己
            $records = ApplyRecord::find()->where(['apply_id'=>$apply->id,'user_id'=>$user_id])->andWhere(['>','step',0])->all();
            if($records)
                $list[] = $apply;
        }

        /*$records = ApplyRecord::find()->where(['user_id'=>Yii::$app->user->id])->all();
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
        }*/
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
                $applyList = Apply::find()->where(['task_id'=>$f->task_id,'status'=>self::STATUS_SUCCESS])->orderBy('add_time desc')->all();
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

    public static function getRelatedList($search,$getCount=false){
        $user_id = Yii::$app->user->id;
        //1.查找操作历史（不包含发起人）中是"我"的
        $records = ApplyRecord::find()->where(['user_id'=>$user_id])->andWhere(['>','step',0])->groupBy('apply_id')->all();

        if(!empty($records)){
            $apply_ids = [];
            foreach($records as $r){
                $apply_ids[] = $r->apply_id;
            }


        /*}


        $flow = Flow::find()->where(['user_id'=>Yii::$app->user->id])->andWhere(['>','step',0])->groupBy('task_id')->select('task_id')->all();
        if(!empty($flow)){
            $taskIds = [];
            foreach($flow as $f){
                $taskIds[] = $f->task_id;
            }*/

            $todoList = self::getTodoList($search);
            $doneList = self::getDoneList($search);
            //$finishList = self::getFinishList();
            $notInIds = [];
            foreach($todoList as $l){
                $notInIds[] = $l->id;
            }
            foreach($doneList as $l){
                $notInIds[] = $l->id;
            }
            /*foreach($finishList as $l){
                $notInIds[] = $l->id;
            }*/

            $query = Apply::find()
                /*->where(['task_id'=>$taskIds])*/
                ->where(['id'=>$apply_ids])
                ->andWhere(['not in','id',$notInIds]);

            foreach($search as $k=>$v){
                if($k=='title' && $v!=''){
                    $query = $query->andWhere(['like','title',$v]);
                }else if($k=='category' && $v!=''){
                    $query = $query->andWhere(['task_category'=>$v]);
                }else if($k=='status' && $v!=''){
                    $query = $query->andWhere(['status'=>$v]);
                }else if($k=='add_time_start' && $v!=''){
                    $query = $query->andWhere(['>=','add_time',$v]);
                }else if($k=='add_time_end' && $v!=''){
                    $query = $query->andWhere(['<=','left(add_time,10)',$v]);
                }
            }

            $query = $query->orderBy('add_time desc');

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


    public static function getCurOperationUser($apply){
        if($apply->status == Apply::STATUS_NORMAL){
            $curRecord = ApplyRecord::find()->where(['apply_id'=>$apply->id,'step'=>$apply->flow_step])->one();
            if($curRecord){
                $user = User::find()->where(['id'=>$curRecord->user_id])->one();
                if($user){
                    return $user->name;
                }else{
                    return '[找不到职员信息]';
                }
            }else{
                return '[找不到当前的操作步骤]';
            }
        }else{
            return '[申请不是执行中状态]';
        }
    }


    public static function getStepUser($apply){
        if($apply->status == self::STATUS_NORMAL){

            /*$stepAllCount = ApplyRecord::find()->where(['apply_id'=>$apply->id])->andWhere('step > 0')->orderBy('step asc')->count();
            $stepNow = $apply->flow_step;*/

            $step = ApplyRecord::find()->where(['apply_id'=>$apply->id,'step'=>$apply->flow_step])->one();

            $step_user = $step?CommonFunc::getByCache(\login\models\UserIdentity::className(),'findIdentityOne',[$step->user_id],'ucenter:user/identity'):false;
            if($step_user)
                $step_username = $step_user->name;
            else
                $step_username = 'N/A';
            
            return $step_username;
            //return $step_username.' ('.$stepNow.'/'.$stepAllCount.')';

        }else{
            return '';
        }


    }
}
