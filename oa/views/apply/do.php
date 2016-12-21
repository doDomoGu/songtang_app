<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use oa\models\OaFlow;
//oa\assets\AppAsset::addJsFile($this,'js/main/apply/create.js');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/do.css');
$this->title = '待办事项';
?>
<section class="panel panel-default" id="infoContent">
    <div class="panel-heading">
        <h3><?=$this->title?></h3>
    </div>
    <div class="panel-body content" >
<?=$html?>
<?php $form = ActiveForm::begin([
    'id' => 'apply-do-form',
    'options' => ['class' => 'form-horizontal','autocomplete'=>'off'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-5\">{input}{hint}</div>\n<div class=\"col-lg-5\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-5" style="padding-top:7px;font-weight:bold;">
        步骤<?=$flow->step?>
    </div>
</div>

<div class="form-group">
    <label class="col-lg-2 control-label" >标题</label>
    <div class="col-lg-5" style="padding-top:7px;font-weight:bold;">
        <?=$flow->title?>
    </div>
</div>

<div class="form-group">
    <label class="col-lg-2 control-label" >操作类型</label>
    <div class="col-lg-5" style="padding-top:7px;font-weight:bold;">
         <?=OaFlow::getTypeCn($flow->type)?>
    </div>
</div>

<?= $form->field($model, 'result')->radioList(OaFlow::getRadioItems($flow->type),['value'=>1]) ?>

<?= $form->field($model, 'message')->textarea() ?>

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?=$html2?>
    </div>
</section>
