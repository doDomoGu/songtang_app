<?php

namespace oa\models;
use Yii;
//oa 流程
class Flow extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }

    const TYPE_APPROVAL = 1;    //审批
    const TYPE_EXAMINE  = 2;    //审核
    const TYPE_EXECUTE  = 3;    //执行
    const TYPE_WATCH    = 4;    //阅览
    const TYPE_FEEDBACK = 5;    //反馈

    const TYPE_APPROVAL_CN = '审批';
    const TYPE_EXAMINE_CN  = '审核';
    const TYPE_EXECUTE_CN  = '执行';
    const TYPE_WATCH_CN    = '阅览';
    const TYPE_FEEDBACK_CN = '反馈';

    const RESULT_APPROVAL_TRUE  = '审批通过';
    const RESULT_APPROVAL_FALSE = '审批不通过';
    const RESULT_EXAMINE_TRUE   = '审核通过';
    const RESULT_EXAMINE_FALSE  = '审核不通过';
    const RESULT_EXECUTE_TRUE   = '执行完成';
    const RESULT_EXECUTE_FALSE  = '执行失败';
    const RESULT_WATCH_TRUE     = '已阅';
    const RESULT_FEEDBACK_TRUE  = '已反馈';

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'task_id' => '对应任务ID',
            'step' => '步骤数',
            'title' => '标题',
            'type' => '操作类型',
            'user_id' => '指定操作人',
            'back_step' => '指定打回的步骤数', //操作类型为 1 approval审核 和 3 execute执行才用到
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['task_id','step','title','type','user_id'], 'required'],
            [['task_id','step','type','user_id','back_step'], 'integer'],
        ];
    }

    public static function getTypeCn($type_id){
        switch ($type_id){
            case self::TYPE_APPROVAL:
                $return = self::TYPE_APPROVAL_CN;
                break;
            case self::TYPE_EXAMINE:
                $return = self::TYPE_EXAMINE_CN;
                break;
            case self::TYPE_WATCH:
                $return = self::TYPE_WATCH_CN;
                break;
            case self::TYPE_EXECUTE:
                $return = self::TYPE_EXECUTE_CN;
                break;
            case self::TYPE_FEEDBACK:
                $return = self::TYPE_FEEDBACK_CN;
                break;
            default:
                $return = 'N/A';
        }
        return $return;
    }

    public function getUser(){
        return $this->hasOne('ucenter\models\User', array('id' => 'user_id'));
    }

    public function getTypeName(){
        switch ($this->type){
            case self::TYPE_APPROVAL:
                $return = self::TYPE_APPROVAL_CN;
                break;
            case self::TYPE_EXAMINE:
                $return = self::TYPE_EXAMINE_CN;
                break;
            case self::TYPE_WATCH:
                $return = self::TYPE_WATCH_CN;
                break;
            case self::TYPE_EXECUTE:
                $return = self::TYPE_EXECUTE_CN;
                break;
            case self::TYPE_FEEDBACK:
                $return = self::TYPE_FEEDBACK_CN;
                break;
            default:
                $return = 'N/A';
        }
        return $return;
    }

    public static function getOptions(){
        $html = '<option value="'.self::TYPE_APPROVAL.'">'.self::TYPE_APPROVAL_CN.'</option>';
        $html .= '<option value="'.self::TYPE_EXAMINE.'">'.self::TYPE_EXAMINE_CN.'</option>';
        $html .= '<option value="'.self::TYPE_WATCH.'">'.self::TYPE_WATCH_CN.'</option>';
        $html .= '<option value="'.self::TYPE_EXECUTE.'">'.self::TYPE_EXECUTE_CN.'</option>';
        $html .= '<option value="'.self::TYPE_FEEDBACK.'">'.self::TYPE_FEEDBACK_CN.'</option>';

        return $html;
    }

    public static function getRadioItems($type){
        switch($type){
            case self::TYPE_APPROVAL:
                $items = [1=>self::RESULT_APPROVAL_TRUE,0=>self::RESULT_APPROVAL_FALSE];
                break;
            case self::TYPE_EXAMINE:
                $items = [1=>self::RESULT_EXAMINE_TRUE,0=>self::RESULT_EXAMINE_FALSE];
                break;
            case self::TYPE_EXECUTE:
                $items = [1=>self::RESULT_EXECUTE_TRUE,0=>self::RESULT_EXECUTE_FALSE];
                break;
            case self::TYPE_WATCH:
                $items = [1=>self::RESULT_WATCH_TRUE];
                break;
            case self::TYPE_FEEDBACK:
                $items = [1=>self::RESULT_FEEDBACK_TRUE];
                break;
            default:
                $items = [];
        }

        return $items;
    }

    public static function getResultCn($type,$result){
        $return = 'N/A';
        switch($type){
            case self::TYPE_APPROVAL:
                if($result==1){
                    $return = self::RESULT_APPROVAL_TRUE;
                }else{
                    $return = self::RESULT_APPROVAL_FALSE;
                }
                break;
            case self::TYPE_EXAMINE:
                if($result ==1){
                    $return = self::RESULT_EXAMINE_TRUE;
                }else{
                    $return = self::RESULT_EXAMINE_FALSE;
                }
                break;
            case self::TYPE_EXECUTE:
                if($result ==1){
                    $return = self::RESULT_EXECUTE_TRUE;
                }else{
                    $return = self::RESULT_EXECUTE_FALSE;
                }
                break;
            case self::TYPE_WATCH:
                if($result == 1){
                    $return = self::RESULT_WATCH_TRUE;
                }
                break;
            case self::TYPE_FEEDBACK:
                if($result == 1){
                    $return = self::RESULT_FEEDBACK_TRUE;
                }
                break;
        }

        return $return;
    }

}
