<?php

namespace yun\models;

use Yii;
use yii\base\Model;

class RecruitmentForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $img_url;
    public $link_url;
    public $ord;
    public $status;
    public $add_time;
    public $edit_time;


    public function attributeLabels(){
        return [
            'title' => '标题',
            'content' => '内容',
            'img_url' => '图片',
            'link_url' => '链接',
            'ord' => '排序',
            'status' => '状态',
        ];
    }

    public function rules()
    {
        return [
            [['title', 'content', 'ord', 'status'], 'required'],
            [['id', 'ord', 'status'], 'integer'],
            [['add_time', 'edit_time', 'img_url', 'link_url'], 'safe']

        ];
    }

}
