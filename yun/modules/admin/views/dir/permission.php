<?php
    use yii\bootstrap\BaseHtml;
    use yun\models\DirPermission;
    use yun\modules\admin\assets\AdminAsset;
    use yun\models\File;

$this->title = '【'.File::getFileFullRoute($dir->id).'】 - 权限编辑';
AdminAsset::addJsFile($this,'js/main/dir/permission.js');
?>
<form id="permission-form" class="form-horizontal" action="" method="post">

<?php if(!empty($dir->dirPermission)):?>
    <?php $i=1;foreach($dir->dirPermission as $dp):?>
    <div class="form-group permission-one" style="background: #d1d1d1;padding:10px 0;">
        <label class="col-lg-2 control-label"><?=$i?></label>
        <div class="col-lg-6">
            <div>
                匹配人员类型：
                <?=BaseHtml::dropDownList('type_'.$i,$dp->type,DirPermission::getTypeItems(),['class'=>'type-select'])?>
            </div>
            <div class="type_param">
                *匹配参数(根据匹配类型不同,选择不同的参数)：
                <span class="type_param_all"> -- </span>
                <span class="type_param_area">地区：<?=BaseHtml::dropDownList('area_'.$i,$dp->area_id,Area::getItems())?></span>
                <span class="type_param_business">业态：<?=BaseHtml::dropDownList('business_'.$i,$dp->business_id,Business::getItems())?></span>
                <span class="type_param_group">权限组：<?=BaseHtml::dropDownList('group_'.$i,'',[])?></span>
                <span class="type_param_user">职员：<?=BaseHtml::dropDownList('user_'.$i,'',[])?></span>
            </div>
            <div>
                操作分类：
                <?=BaseHtml::dropDownList('operation_'.$i,$dp->operation,DirPermission::getOperationItems())?>
            </div>
            <div>
                模式类型：
                <?=BaseHtml::dropDownList('mode_'.$i,$dp->mode,DirPermission::getModeItems())?>
            </div>
        </div>
    </div>
    <?php $i++;endforeach;?>
<?php endif;?>



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
