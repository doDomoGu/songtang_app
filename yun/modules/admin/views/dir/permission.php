<?php
    use yii\bootstrap\BaseHtml;
    use yun\models\DirPermission;
    use yun\modules\admin\assets\AdminAsset;
    use yun\models\File;

$this->title = '【'.File::getFileFullRoute($dir->id).'】 - 权限编辑';
AdminAsset::addJsFile($this,'js/main/dir/permission.js');
?>
<form id="permission-form" class="form-horizontal" action="" method="post">
<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>文件属性限制</th>
        <th>匹配类型</th>
        <th>匹配参数</th>
        <th>操作类型</th>
        <th>模式</th>
    </tr>
<?php if(!empty($permission_list)):?>
    <?php $i=1;foreach($permission_list as $dp):?>
    <tr>
        <td>
            <?=$i?>
        </td>
        <td>
            <?=BaseHtml::dropDownList('permission_type_'.$i,$dp->permission_type,DirPermission::getPermissionTypeItems(),['class'=>'permission-type-select'])?>
        </td>
        <td>
            <?=BaseHtml::dropDownList('user_match_type_'.$i,$dp->user_match_type,DirPermission::getTypeItems(),['class'=>'user-match-type-select'])?>
        </td>
        <td>
            <div class="user_match_param_div user_match_param_1_div" style="display: none;">
                --
                <?=BaseHtml::hiddenInput('user_match_param_'.$i.'_1',0)?>
            </div>
            <div class="user_match_param_div user_match_param_7_div" style="display: none;">
                用户组ID：<?=BaseHtml::textInput('user_match_param_'.$i.'_7',$dp->user_match_type==7?$dp->user_match_param_id:'')?>
            </div>
        </td>
        <td>
            <?=BaseHtml::dropDownList('operation_'.$i,$dp->operation,DirPermission::getOperationItems())?>

        </td>
        <td>
            <?=BaseHtml::dropDownList('mode_'.$i,$dp->mode,DirPermission::getModeItems())?>
        </td>
    </tr>
    <!--<div class="form-group permission-one" style="background: #d1d1d1;padding:10px 0;">
        <label class="col-lg-2 control-label"><?/*=$i*/?></label>
        <div class="col-lg-6">
            <div>
                匹配人员类型：
                <?/*=BaseHtml::dropDownList('type_'.$i,$dp->permission_type,DirPermission::getTypeItems(),['class'=>'type-select'])*/?>
            </div>
            <div class="type_param">
                *匹配参数(根据匹配类型不同,选择不同的参数)：
                <span class="type_param_all"> -- </span>
                <span class="type_param_area">地区：<?/*=BaseHtml::dropDownList('area_'.$i,$dp->area_id,Area::getItems())*/?></span>
                <span class="type_param_business">业态：<?/*=BaseHtml::dropDownList('business_'.$i,$dp->business_id,Business::getItems())*/?></span>
                <span class="type_param_group">权限组：<?/*=BaseHtml::dropDownList('group_'.$i,'',[])*/?></span>
                <span class="type_param_user">职员：<?/*=BaseHtml::dropDownList('user_'.$i,'',[])*/?></span>
            </div>
            <div>
                操作分类：
                <?/*=BaseHtml::dropDownList('operation_'.$i,$dp->operation,DirPermission::getOperationItems())*/?>
            </div>
            <div>
                模式类型：
                <?/*=BaseHtml::dropDownList('mode_'.$i,$dp->mode,DirPermission::getModeItems())*/?>
            </div>
        </div>
    </div>-->
    <?php $i++;endforeach;?>
<?php endif;?>



</table>

    <!--<div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary" name="login-button">提交</button>
        </div>
    </div>-->

</form>
