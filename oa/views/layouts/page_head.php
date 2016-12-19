<?php
    use yii\bootstrap\Html;
?>
<div id="page_head">
    <?=Html::a('发起申请','/apply/create',['class'=>'btn btn-success'])?>
    <br/>
    <br/>
    <?=Html::a('我的申请>>','/apply/my',['class'=>'btn btn-primary'])?>
    <?=Html::a('待办事项>>','/apply/todo',['class'=>'btn btn-primary'])?>
    <?=Html::a('相关事项>>','/apply/related',['class'=>'btn btn-primary'])?>
    <?=Html::a('完结事项>>','/apply/finish',['class'=>'btn btn-primary'])?>
</div>