<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yun\components\DirFunc;

$this->title = '【'.DirFunc::getFileFullRoute($dir->id).'】 - 权限编辑'
?>
<form id="permission-form" class="form-horizontal" action="/admin/dir/add-and-edit?id=3" method="post">

    <div class="form-group">
        <label class="col-lg-2 control-label">1</label>
        <div class="col-lg-6">
            <input id="dirform-name" class="form-control" name="DirForm[name]" value="颂唐机构及旗下品牌LOGO标志" type="text">
        </div>
    </div>
<!--
    <div class="form-group field-dirform-name required">
        <label class="col-lg-2 control-label" for="dirform-name">名称</label>
        <div class="col-lg-5"><input id="dirform-name" class="form-control" name="DirForm[name]" value="颂唐机构及旗下品牌LOGO标志" type="text"></div>
        <div class="col-lg-5"><p class="help-block help-block-error"></p></div>
    </div>
    <div class="form-group field-dirform-alias required">
        <label class="col-lg-2 control-label" for="dirform-alias">别名</label>
        <div class="col-lg-5"><input id="dirform-alias" class="form-control" name="DirForm[alias]" value="logo" disabled="" type="text"></div>
        <div class="col-lg-5"><p class="help-block help-block-error"></p></div>
    </div>
    <div class="form-group field-dirform-link">
        <label class="col-lg-2 control-label" for="dirform-link">链接</label>
        <div class="col-lg-5"><input id="dirform-link" class="form-control" name="DirForm[link]" value="" type="text"><p class="help-block">填写后，会使这个目录转变一个链接，谨慎填写</p></div>
        <div class="col-lg-5"><p class="help-block help-block-error"></p></div>
    </div>-->
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary" name="login-button">提交</button>
        </div>
    </div>

</form>
