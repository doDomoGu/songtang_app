<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

oa\assets\AppAsset::addJsFile($this,'js/main/apply/create.js');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/create.css');
$this->title = '发起申请';
?>

<section class="panel panel-default">
    <div class="panel-heading">
        <?=Html::img('/images/main/apply/apply_heading_1.png')?>
        <?=$this->title?>
        <?/*=Html::img('/images/main/apply/create-icon-2.png')*/?>
    </div>
    <div class="panel-body">

<?php $form = ActiveForm::begin([
    'id' => 'apply-create-form',
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'validateOnSubmit' => true,
    'options' => ['class' => 'form-horizontal','autocomplete'=>'off'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-7\">{input}{hint}</div>\n<div class=\"col-lg-2\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ],
]); ?>
<?= $form->field($model, 'title')->label(Html::img('/images/main/apply/create-head.png').'&nbsp;&nbsp;'.$model->attributeLabels()['title'],['style'=>'text-align:left;padding-left:60px;']) ?>

<?= $form->field($model, 'task_category',[
    'template'=>"{label}\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-2\">{error}</div><div class=\"col-lg-offset-3 col-lg-7 \"></div>"
])->dropDownList($taskCategory,['prompt'=>'==请选择==','class'=>['form-control task-category-select'],'encode'=>false ])->label(Html::img('/images/main/apply/create-head-2.png').'&nbsp;&nbsp;'.$model->attributeLabels()['task_category'],['style'=>'text-align:left;padding-left:60px;'])  ?>


<?= $form->field($model, 'task_id',[
    'template'=>"{label}\n<div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-2\">{error}</div><div class=\"col-lg-offset-3 col-lg-7 task-preview \"></div>"
])->dropDownList([],['prompt'=>'==请选择==','class'=>['form-control task-select']])->label(Html::img('/images/main/apply/create-head-2.png').'&nbsp;&nbsp;'.$model->attributeLabels()['task_id'],['style'=>'text-align:left;padding-left:60px;'])  ?>


<?= $form->field($model, 'message')->textarea(['rows'=>10])->label(Html::img('/images/main/apply/create-head-3.png').'&nbsp;&nbsp;'.$model->attributeLabels()['message'],['style'=>'text-align:left;padding-left:60px;'])   ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
    </div>
</section>
