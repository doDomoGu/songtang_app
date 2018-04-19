<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

oa\assets\AppAsset::addJsFile($this,'js/main/apply/create.js');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/create.css');
oa\assets\AppAsset::addJsFile($this,'https://yun-source.songtang.net/resource/js/qiniu/plupload.full.min.js');
oa\assets\AppAsset::addJsFile($this,'https://yun-source.songtang.net/resource/js/qiniu/qiniu.min.js');
oa\assets\AppAsset::addJsFile($this,'js/main/apply/create-apply-attachment-upload.js');
/*oa\assets\AppAsset::addJsFile($this,'js/bootstrap-datetimepicker.js');
oa\assets\AppAsset::addCssFile($this,'css/datetimepicker/bootstrap-datetimepicker.css');
oa\assets\AppAsset::addCssFile($this,'css/datetimepicker/datetimepicker-kv.css');*/
oa\assets\AppAsset::addJsFile($this,'script/jquery-ui/jquery-ui.min.js');
oa\assets\AppAsset::addCssFile($this,'script/jquery-ui/jquery-ui.min.css');


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
    'options' => ['class' => 'form-horizontal','autocomplete'=>'off','onkeydown'=>'if(event.keyCode==13)return false;'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-7\">{input}{hint}</div>\n<div class=\"col-lg-2\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ],
]); ?>
<?= $form->field($model, 'title',['template'=>"{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-lg-9\">{error}</div><div class=\"col-lg-offset-3 col-lg-9 \"></div>"
])->label(Html::img('/images/main/apply/create-head.png').'&nbsp;&nbsp;'.$model->attributeLabels()['title'],['style'=>'text-align:left;padding-left:60px;']) ?>

<?= $form->field($model, 'task_category',[
    'template'=>"{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-lg-9\">{error}</div><div class=\"col-lg-offset-3 col-lg-9 \"></div>"
])->dropDownList($taskCategory,['prompt'=>'==请选择==','class'=>['form-control task-category-select'],'encode'=>false ])->label(Html::img('/images/main/apply/create-head-2.png').'&nbsp;&nbsp;'.$model->attributeLabels()['task_category'],['style'=>'text-align:left;padding-left:60px;'])  ?>


<?= $form->field($model, 'task_id',[
    'template'=>"{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-lg-9\">{error}</div><div class=\"col-lg-12 task-preview \" style=\"width:920px;\"></div>"
])->dropDownList([],['prompt'=>'==请选择==','class'=>['form-control task-select']])->label(Html::img('/images/main/apply/create-head-2.png').'&nbsp;&nbsp;'.$model->attributeLabels()['task_id'],['style'=>'text-align:left;padding-left:60px;'])  ?>

<?= $form->field($model, 'form_id',[
    'template'=>"{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-lg-9\">{error}</div><div class=\"col-lg-12 form-content\" style=\"width:920px;\"></div>"
])->dropDownList([],['prompt'=>'==请选择==','class'=>['form-control form-select']])->label(Html::img('/images/main/apply/create-head-2.png').'&nbsp;&nbsp;'.$model->attributeLabels()['form_id'],['style'=>'text-align:left;padding-left:60px;'])  ?>



<?= $form->field($model, 'message',['template'=>"{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-lg-9\">{error}</div><div class=\"col-lg-offset-3 col-lg-9 \"></div>"
])->textarea(['rows'=>10])->label(Html::img('/images/main/apply/create-head-3.png').'&nbsp;&nbsp;'.$model->attributeLabels()['message'],['style'=>'text-align:left;padding-left:60px;'])   ?>


<div class="form-group">
    <label class="col-lg-3 control-label" >附件</label>
    <div class="col-lg-9" style="padding-top:7px;font-weight:bold;" id="pickfile_container">
        <input type="file" id="pickfile">
        <input type="hidden" id="fileurl" name="fileurl" />
    </div>

    <div class="col-lg-offset-3 col-lg-8">
        <div id="upload_files">

        </div>
        <div id="upload_progress" style="display:non33e;">
        </div>
    </div>

</div>


<input type="hidden" id="qiniuDomain" value="<?=yii::$app->params['qiniu-domain']?>" />


<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'login-button','id'=>'create-submit']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
    </div>
</section>
