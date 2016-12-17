<?php

namespace oa\models;

//oa 流程
class OaFlow extends \yii\db\ActiveRecord
{
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

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'task_id' => '对应任务ID',
            'step' => '步骤数',
            'title' => '标题',
            'type' => '操作类型',
            'user_id' => '指定操作人',
            'status' => '状态'
        ];
    }

    public function rules()
    {
        return [
            [['task_id','step','title','type','user_id'], 'required'],
            [['task_id','step','type','user_id'], 'integer'],
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

    public static function getOptions(){
        $html = '<option value="'.self::TYPE_APPROVAL.'">'.self::TYPE_APPROVAL_CN.'</option>';
        $html .= '<option value="'.self::TYPE_EXAMINE.'">'.self::TYPE_EXAMINE_CN.'</option>';
        $html .= '<option value="'.self::TYPE_WATCH.'">'.self::TYPE_WATCH_CN.'</option>';
        $html .= '<option value="'.self::TYPE_EXECUTE.'">'.self::TYPE_EXECUTE_CN.'</option>';
        $html .= '<option value="'.self::TYPE_FEEDBACK.'">'.self::TYPE_FEEDBACK_CN.'</option>';

        return $html;
    }

}
