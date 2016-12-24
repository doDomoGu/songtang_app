<?php

namespace yun\modules\admin\models;

use Yii;
use yii\base\Model;

class DirForm extends Model
{
    public $id;
    public $name;
    public $alias;
    public $describe;
    public $type;
    //public $more_cate;
    public $p_id;
    public $link;
    public $level;
    public $is_leaf;
    public $is_last;
    public $ord;
    public $status;

    public function attributeLabels(){
        return [
            'name' => '名称',
            'alias' => '别名',
            'describe' => '描述',
            'type' => '类型',
            'p_id' => '父级',
            'link' => '链接',
            'level' => '层级',
            'is_leaf' => '是否底层目录',
            //'is_last' => '最后一个',
            'ord' => '排序',
            'status' => '状态',
        ];
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'p_id'], 'required'],
            [['id', 'type', 'ord', 'level', 'is_leaf', 'is_last', 'p_id', 'status'], 'integer'],
            [['describe','link'], 'safe']

        ];
    }

}
