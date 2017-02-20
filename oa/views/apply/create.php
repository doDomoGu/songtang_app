<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

oa\assets\AppAsset::addJsFile($this,'js/main/apply/create.js');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/create.css');
$this->title = '发起申请';
?>

<section class="panel panel-default">
    <div class="panel-heading">
        <h3><?=Html::img('/images/main/apply/create-icon.png')?> &nbsp;&nbsp;&nbsp;<?=$this->title?></h3>
    </div>
    <div class="panel-body">


<?php $form = ActiveForm::begin([
    'id' => 'apply-create-form',
    'options' => ['class' => 'form-horizontal','autocomplete'=>'off'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-5\">{input}{hint}</div>\n<div class=\"col-lg-5\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>
<?= $form->field($model, 'title') ?>

<?= $form->field($model, 'task_id',[
    'template'=>"{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div><div class=\"col-lg-offset-2 col-lg-10 task-preview \"></div>"
])->dropDownList($tasks,['prompt'=>'==请选择==','class'=>['form-control task-select']])  ?>


<?= $form->field($model, 'message')->textarea(['rows'=>10]) ?>

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
    </div>
</section>
