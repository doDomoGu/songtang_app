<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
$this->title = '';
?>
<div>
    <?=Html::a('发起申请','/apply/create',['class'=>'btn btn-success'])?>
    <br/>
    <br/>
    <?=Html::a('待办事项>>','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-primary'])?>
    <?=Html::a('相关事项>>','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-primary'])?>
    <?=Html::a('完结事项>>','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-primary'])?>
</div>

