<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use oa\models\Flow;


oa\assets\AppAsset::addJsFile($this,'js/main/apply/operation.js');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/operation.css');
oa\assets\AppAsset::addJsFile($this,'js/qiniu/plupload.full.min.js');
oa\assets\AppAsset::addJsFile($this,'js/qiniu/qiniu.js');
oa\assets\AppAsset::addJsFile($this,'js/main/apply/attachment-upload.js');


oa\assets\AppAsset::addCssFile($this,'css/main/apply/do.css');
$this->title = '待办事项';
?>
<section class="panel panel-default" id="infoContent">
    <div class="panel-heading">
        <?=Html::img('/images/main/apply/apply_heading_2.png')?>
        <?=$this->title?>
        <?/*=Html::img('/images/main/apply/create-icon-2.png')*/?>
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
         <?=Flow::getTypeCn($flow->type)?>
    </div>
</div>

<?= $form->field($model, 'result')->radioList(Flow::getRadioItems($flow->type),['value'=>1]) ?>

<?= $form->field($model, 'message')->textarea(['cols'=>190,'rows'=>10]) ?>


<div class="form-group">
    <label class="col-lg-2 control-label" >附件</label>
    <div class="col-lg-8" style="padding-top:7px;font-weight:bold;" id="pickfile_container">
        <input type="file" id="pickfile">
        <input type="hidden" id="fileurl" name="fileurl" />
    </div>

    <div class="col-lg-offset-2 col-lg-8">
        <div id="upload_files">

        </div>
        <div id="upload_progress" style="display:non33e;">
        </div>
    </div>

</div>


        <input type="hidden" id="qiniuDomain" value="<?=yii::$app->params['qiniu-domain']?>" />
        <input type="hidden" id="apply_id" value="<?=$apply->id?>" />
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'submit-button','id'=>'submit-button']) ?>
    </div>
</div>

        <!--<div class="form-group">
            <label class="col-lg-2 control-label" >操作类型</label>
            <div class="col-lg-5" style="padding-top:7px;font-weight:bold;">
                <?/*=Flow::getTypeCn($flow->type)*/?>
            </div>
        </div>-->

<?php ActiveForm::end(); ?>
<input id="apply_id" value="<?=$apply->id?>" type="hidden" />
<?=$html2?>
    </div>

<?=$this->render('file_preview_modal')?>
</section>



