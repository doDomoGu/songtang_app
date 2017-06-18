<?php

namespace oa\models;

//oa 模板和表单的对应关系

use Yii;
use yii\helpers\Html;

class FormItem extends \yii\db\ActiveRecord
{
    const TYPE_NULL = 0;
    const TYPE_TEXT = 1;
    const TYPE_NUMBER = 2;
    const TYPE_RADIO = 3;
    const TYPE_CHECKBOX = 4;
    const TYPE_DATE = 5;

    const TYPE_NULL_CN = 'N/A';
    const TYPE_TEXT_CN = '文本';
    const TYPE_NUMBER_CN = '数字';
    const TYPE_RADIO_CN = '单选';
    const TYPE_CHECKBOX_CN = '多选';
    const TYPE_DATE_CN = '日期';


    public static function getDb(){
        return Yii::$app->db_oa;
    }

    public function attributeLabels(){
        return [
            'form_id' =>'表单ID',
            'ord' =>'排序',
            'item_key' =>'key',
            'item_value' =>'value',
            'status' =>'状态',
        ];
    }

    public function rules()
    {
        return [
            [['form_id','ord','item_key','item_value'], 'required'],
            [['form_id','ord','status'], 'integer']
        ];
    }

    /*
     *
     * value 数组结构 （以json格式存入item_value)
     * label: 标签名称  例如：请假表中的"天数"，"请假日期"，"请假类型"
     * type: 选项类型  text:文本类型,number:数字类型,date:日期类型,radio:单选,checkbox:多选 等等
     * options: 当type为radio和checkbox时支持 一维数组，下标为自然排序 从0开始
     *
     *
     *
     */


    public static function itemType(){
        return [
            self::TYPE_NULL => self::TYPE_NULL_CN,
            self::TYPE_TEXT => self::TYPE_TEXT_CN,
            self::TYPE_NUMBER => self::TYPE_NUMBER_CN,
            self::TYPE_RADIO => self::TYPE_RADIO_CN,
            self::TYPE_CHECKBOX => self::TYPE_CHECKBOX_CN,
            self::TYPE_DATE => self::TYPE_DATE_CN
            /*'datetime' =>'日期和时间',
            'time' =>'时间',
            'range' =>'时间范围',*/
        ];
    }


    public static function jsonDecodeValue($value,$key=false,$getContent=false){
        $return = [
            'label'=> '',
            'type'=> 0,
            'type_cn'=> '',
            'options'=> ''
        ];

        $arr = json_decode($value,true);
        if($arr){
            if(isset($arr['label']) && isset($arr['type'])){
                $return['label'] = $arr['label'];
                $return['type'] = isset($arr['type'])?$arr['type']:self::TYPE_NULL;
                $itemType = self::itemType();
                $return['type_cn'] = isset($itemType[$arr['type']])?$itemType[$arr['type']]:$itemType[self::TYPE_NULL];
                if(isset($arr['options']) && is_array($arr['options'])){
                    $return['options'] = implode('<br/>' ,$arr['options']);
                }

                if($getContent)
                    $return['itemContent'] = self::generateItemContent($return['type'],$key,$arr['options']);
            }
        }
        return $return;
    }

    public static function generateItemContent($type,$key,$options){
        $input_key = 'form_item['.$key.']';
        switch($type){
            case self::TYPE_TEXT:
            case self::TYPE_NUMBER:
                $content = Html::textInput($input_key);
                break;
            case self::TYPE_RADIO:
                if(!is_array($options)){
                    $options = [];
                }
                $content = Html::radioList($input_key,null,$options);
                break;
            case self::TYPE_CHECKBOX:
                if(!is_array($options)){
                    $options = [];
                }
                $content = Html::checkboxList($input_key,null,$options);
                break;
            default:
                $content = '';
        }
        return $content;
    }


    /*public function getUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getTask(){
        return $this->hasOne(Task::className(), array('id' => 'task_id'));
    }*/

}
