<?php

namespace oa\models;

//oa 模板和表单的对应关系

use kartik\datetime\DateTimePicker;
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
    const TYPE_TABLE = 6;
    const TYPE_TEXTAREA = 7;
    const TYPE_DROPDOWN = 8;

    const TYPE_NULL_CN = 'N/A';
    const TYPE_TEXT_CN = '文本';
    const TYPE_NUMBER_CN = '数字';
    const TYPE_RADIO_CN = '单选';
    const TYPE_CHECKBOX_CN = '多选';
    const TYPE_DATE_CN = '日期';
    const TYPE_TABLE_CN = '表格';
    const TYPE_TEXTAREA_CN = '多行文本';
    const TYPE_DROPDOWN_CN = '下拉框';


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
     * label_width: 标签的宽度  单位：像素
     * input_type: 选项类型(数值）  text:文本类型,number:数字类型,date:日期类型,radio:单选,checkbox:多选 等等
     * input_type_cn: 选项类型(中文名称)  text:文本类型,number:数字类型,date:日期类型,radio:单选,checkbox:多选 等等
     * input_width: 选项的宽度   单位：像素
     * input_options: 当type为radio和checkbox时支持 一维数组，下标为自然排序 从0开始
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
            self::TYPE_DATE => self::TYPE_DATE_CN,
            self::TYPE_TABLE => self::TYPE_TABLE_CN,
            self::TYPE_TEXTAREA => self::TYPE_TEXTAREA_CN,
            self::TYPE_DROPDOWN => self::TYPE_DROPDOWN_CN
            /*'datetime' =>'日期和时间',
            'time' =>'时间',
            'range' =>'时间范围',*/
        ];
    }


    public static function jsonDecodeValue($value,$key=false,$getContent=false){
        $return = [
            'label'=> '',
            'label_width'=> 120,
            'input_type'=> 0,
            'input_type_cn'=> '',
            'input_width'=> 768,
            'input_options'=> '',
            'input_options_html'=> '',
            'value'=>'',
            'itemContent' => ''
        ];

        $arr = json_decode($value,true);
        if($arr){
            if(isset($arr['label']) && isset($arr['input_type'])){
                $return['label'] = $arr['label'];
                if(isset($arr['label_width']))
                    $return['label_width'] = $arr['label_width'];
                $itemType = self::itemType();
                if(isset($arr['input_type'])){
                    $return['input_type'] = $arr['input_type'];
                    if(isset($itemType[$arr['input_type']])){
                        $return['input_type_cn'] = $itemType[$arr['input_type']];
                    }else{
                        $return['input_type_cn'] = $itemType[self::TYPE_NULL];
                    }
                }else{
                    $return['input_type'] = self::TYPE_NULL;
                    $return['input_type_cn'] = $itemType[self::TYPE_NULL];
                }

                if(isset($arr['input_width']))
                    $return['input_width'] = $arr['input_width'];
                if(isset($arr['input_options']) && is_array($arr['input_options'])){
                    $return['input_options'] = $arr['input_options'];
                    $return['input_options_html'] = implode("\n" ,$arr['input_options']);
                }
                if(isset($arr['value']))
                    $return['value'] = $arr['value'];

                if($getContent)
                    $return['itemContent'] = self::generateItemContent($return['input_type'],$key,$return['input_options'],$return['value']);
            }
        }
        return $return;
    }

    public static function generateItemContent($type,$key,$options,$value){
        $input_key = 'form_item['.$key.']';
        switch($type){
            case self::TYPE_TEXT:
                $content = Html::textInput($input_key,$value);
                break;
            case self::TYPE_NUMBER:
                $width = false;
                $unit = false;
                foreach($options as $o){
                    $temp = explode(':',$o);
                    if(count($temp)==2){
                        if($temp[0]=='unit'){
                            $unit = $temp[1];
                        }elseif($temp[0]=='width') {
                            $width = $temp[1];
                        }
                    }
                }
                $options2 = $width?['style'=>'width:'.$width.'px']:[];
                $content = Html::textInput($input_key,$value,$options2);
                $content .= $unit?'<span class="num-unit">'.$unit.'</span>':'';
                break;
            case self::TYPE_TEXTAREA:
                $content = Html::textarea($input_key,$value);
                break;
            case self::TYPE_RADIO:
                if(!is_array($options)){
                    $options = [];
                }
                $content = Html::radioList($input_key,$value,$options);
                break;
            case self::TYPE_DROPDOWN:
                if(!is_array($options)){
                    $options = [];
                }
                $content = Html::dropDownList($input_key,$value,$options);
                break;
            case self::TYPE_CHECKBOX:
                if(!is_array($options)){
                    $options = [];
                }
                $content = Html::checkboxList($input_key,$value,$options);
                break;
            case self::TYPE_DATE:
                $content = Html::textInput($input_key,$value,['class'=>'datepicker-x']);
                break;
            /*case self::TYPE_DATE:
                $format = isset($options['format'])?$options['format']:'yyyy-mm-dd';
                $content = DateTimePicker::widget([

                    'name' => $input_key,
                    'value'=>$value,
                    'layout'=>'{picker}{input}',

                    'options' => ['placeholder' => '选择日期','class'=>'datepicker-x'],
                    'pluginOptions' => [
                        'format' => $format,
                        'startDate' => '2014-01-01',
                        'todayHighlight' => true,
                        'minView'=> "month",
                        'autoclose'=>1
                    ]
                ]);
                break;*/
            case self::TYPE_TABLE:
                $content = '';
                if(is_array($options)){
                    $options2 = [];
                    $line = 0;
                    foreach($options as $o){
                        $temp = explode(':',$o);
                        if(count($temp)==2 && $temp[0]=='line'){
                            $line = $temp[1];
                        }else if(count($temp)==3){
                            $options2[$temp[0]] = ['title'=>$temp[1],'width'=>$temp[2]];
                        }else if(count($temp)==4){
                            $options2[$temp[0]] = ['title'=>$temp[1],'width'=>$temp[2],'type'=>$temp[3]];
                        }else if(count($temp)==5){
                            $options2[$temp[0]] = ['title'=>$temp[1],'width'=>$temp[2],'type'=>$temp[3],'options'=>$temp[4]];
                        }
                    }

                    //表格标题行
                    foreach($options2 as $k=>$v){
                        $content .= '<span class="table-item-label" style="width:'.$v['width'].'px;">'.$v['title'].'</span>';
                        //$content = Html::checkboxList($input_key,$value,$options);
                    }

                    for($i=0;$i<$line;$i++){
                        foreach($options2 as $k=>$v){
                            $content .= '<span class="table-item-input" style="width:'.$v['width'].'px;">';
                            if(isset($v['type'])){
                                if($v['type']==self::TYPE_DATE){
                                    $content .= Html::textInput($input_key.'['.$i.']['.$k.']',isset($value[$i][$k])?$value[$i][$k]:'',['class'=>'datepicker-x']);
                                }else if($v['type']==self::TYPE_NUMBER){
                                    $content .= Html::textInput($input_key.'['.$i.']['.$k.']',isset($value[$i][$k])?$value[$i][$k]:'',['style'=>'width:'.($v['width']-30).'px']). '&nbsp;'.$v['options'];
                                }else if($v['type']==self::TYPE_DROPDOWN){
                                    $dropDownItems = explode('|',$v['options']);
                                    $content .= Html::dropDownList($input_key.'['.$i.']['.$k.']',isset($value[$i][$k])?$value[$i][$k]:'',$dropDownItems,['style'=>'width:96%;','prompt'=>'--请选择--']);
                                }else{
                                    $content .= Html::textInput($input_key.'['.$i.']['.$k.']',isset($value[$i][$k])?$value[$i][$k]:'');
                                }
                            }else{
                                $content .= Html::textInput($input_key.'['.$i.']['.$k.']',isset($value[$i][$k])?$value[$i][$k]:'');
                            }
                            $content .= '</span>';
                            //$content = Html::checkboxList($input_key,$value,$options);
                        }
                    }




                }
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

    //用来当删除一个选项时将他之后的所有选项往前移一位
    public static function ordUpAll($form_id,$ord){
        $items = self::find()->where(['form_id'=>$form_id])->andWhere(['>=','ord',$ord])->all();
        foreach($items as $item){
            $item->ord--

            ;
            $item->save();
        }
    }

    //用来当插入一个选项时将他之后的所有选项往后移一位
    public static function ordDownAll($form_id,$ord){
        $items = self::find()->where(['form_id'=>$form_id])->andWhere(['>=','ord',$ord])->all();
        foreach($items as $item){
            $item->ord++;
            $item->save();
        }
    }


    public static function getPositionList($form_id){
        $list = [
            'first'=> '最前'
        ];
        $items = self::find()->where(['form_id'=>$form_id])->orderBy('ord asc')->all();
        if(!empty($items)){
            foreach($items as $item){
                $list[$item['ord']] = '在"'.$item['item_key'].'"之后';
            }
        }


        $list['last'] = '最后';
        return $list;
    }


    public static function getHtmlByForm($form_id){
        $formContentHtml = '';
        $form = Form::find()->where(['id'=>$form_id])->one();
        $formItems = FormItem::find()->where(['form_id'=>$form_id,'status'=>1])->orderBy('ord asc')->all();
        if($formItems) {
            $formContentHtml .= '<section class="task-form-section">' .
                '<h1>申请表 -【'.$form->title.'】- 表单：</h1>';
            //$i = 0;
            $formContentHtml .=self::getHtmlByFormItem($formItems);

            $formContentHtml .= '</section>';
        }
        return $formContentHtml;

    }


    public static function getHtmlByFormItem($formItems){
        $formContentHtml = '';
        foreach ($formItems as $item) {

            $valueArr = FormItem::jsonDecodeValue($item->item_value, $item->item_key, true);
            //$i++;
            $htmlOne = '<li class="form-item type-'.$valueArr['input_type'].'">';
            if($valueArr['label_width']>0) {
                $htmlOne .= '<span class="item-label" style="width:' . $valueArr['label_width'] . 'px">' . $valueArr['label'] . '</span>';
            }
            $htmlOne .= '<span class="item-content" style="width:' . $valueArr['input_width'] . 'px">' . $valueArr['itemContent'] . '</span>';
            /*$htmlOne.= '<div class="task-preview-step">步骤'.$f->step.'</div>';
            $htmlOne.= '<div>标题：'.$f->title.'</div>';
            $htmlOne.= '<div>类型：'.$f->typeName.'</div>';
            $htmlOne.= '<div>转发：'.($f->enable_transfer==1?'允许':'禁止').'</div>';

            $htmlOne.= '<div>操作人：'.$operation_user.'</div>';*/
            $htmlOne .= '</li>';
            $formContentHtml .= $htmlOne;

        }

        return $formContentHtml;

    }
}
