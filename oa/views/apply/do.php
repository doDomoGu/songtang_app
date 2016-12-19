<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
oa\assets\AppAsset::addJsFile($this,'js/main/apply/create.js');
$this->title = '进行操作';
?>
<?php $form = ActiveForm::begin([
    'id' => 'apply-do-form',
    'options' => ['class' => 'form-horizontal','autocomplete'=>'off'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-5\">{input}{hint}</div>\n<div class=\"col-lg-5\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>
<?= $form->field($model, 'result') ?>

<?= $form->field($model, 'message')->textarea() ?>

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
