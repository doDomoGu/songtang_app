<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    app\assets\AppAsset::addJsFile($this,'js/qiniu/plupload.full.min.js');
    app\assets\AppAsset::addJsFile($this,'js/qiniu/qiniu.js');
    app\assets\AppAsset::addJsFile($this,'js/user-change-head-img.js');
?>
<input type="hidden" id="qiniuDomain" value="<?=yii::$app->params['qiniu-domain']?>" />
<input type="hidden" id="qiniuDomainBeaut" value="<?=yii::$app->params['qiniu-domain-beaut']?>" />
<input type="hidden" id="pickfileId" value="pickfile" />
<input type="hidden" id="fileurlId" value="fileurl" />
<input type="hidden" id="userId" value="<?=$model->id?>" />
<div>
    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <!--<p>Please fill out the following fields to login:</p>-->

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'head_img',['template'=>"{label}\n<div class=\"col-lg-3\">
    <div>
    <img id=\"img-upload\" src=\"".app\components\CommonFunc::imgUrl($model->head_img)."\" style=\"border:1px solid #333;width:300px;height:300px;\" />
    </div>
    <div id=\"pickfile_container\">
    <p>
        <input type=\"file\" id=\"pickfile\">
        <br/>
        <b>请上传 300*300 的照片</b>
    </p>
    <p>
    <input type=\"hidden\" name=\"UserChangeHeadImgForm[head_img]\" class=\"form-control\" value=\"".$model->head_img."\" id=\"form-head_img\">

    </p>
    <div class=\"clearfix\" id=\"fileurl_upload_txt\"></div>
</div></div>\n<div class=\"col-lg-5\">{error}</div>"
    ]) ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                <?= Html::a('返回', '/user',['class' => 'btn btn-success','style'=>'margin-left:20px;']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
