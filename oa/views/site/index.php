<?php
use yii\helpers\Html;

$this->title = '';

?>
<div>
    <?=Html::a('发起申请','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
    <?=Html::a('待办事项>>','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
    <?=Html::a('相关事项>>','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
    <?=Html::a('完结事项>>','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
</div>
