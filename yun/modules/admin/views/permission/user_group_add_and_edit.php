<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    yun\assets\AppAsset::addJsFile($this,'js/qiniu/plupload.full.min.js');
    yun\assets\AppAsset::addJsFile($this,'js/qiniu/qiniu.js');
    yun\assets\AppAsset::addJsFile($this,'js/news-add-and-edit.js');
?>
<input type="hidden" id="qiniuDomain" value="<?=yii::$app->params['qiniu-domain']?>" />
<input type="hidden" id="qiniuDomainBeaut" value="<?=yii::$app->params['qiniu-domain-beaut']?>" />
<input type="hidden" id="pickfileId" value="pickfile" />
<input type="hidden" id="fileurlId" value="fileurl" />
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'title') ?>

        <?= $form->field($model, 'content') ?>

        <?= $form->field($model, 'img_url',['template'=>"{label}\n<div class=\"col-lg-3\">
    <div>
    <img id=\"img-upload\" src=\"".yun\components\CommonFunc::imgUrl($model->img_url)."\" style=\"border:1px solid #333;\" />
    </div>
    <div id=\"pickfile_container\">
    <p>
        <input type=\"file\" id=\"pickfile\">
    </p>
    <p>
    <input type=\"hidden\" name=\"NewsForm[img_url]\" class=\"form-control\" value=\"".$model->img_url."\" id=\"newsform-img_url\">

    </p>
    <div class=\"clearfix\" id=\"fileurl_upload_txt\"></div>
</div></div>\n<div class=\"col-lg-5\">{error}</div>"
        ]) ?>



        <?= $form->field($model, 'link_url') ?>

        <?= $form->field($model, 'ord',[
            'template'=>"{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>"
        ])->dropDownList([5=>5,4=>4,3=>3,2=>2,1=>1])  ?>

        <?= $form->field($model, 'status',[
            'template'=>"{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>"
        ])->dropDownList([0=>'禁用',1=>'启用']) ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
