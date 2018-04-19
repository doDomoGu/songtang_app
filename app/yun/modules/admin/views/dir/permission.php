<?php
    use yii\bootstrap\BaseHtml;
    use yun\models\DirPermission;
    use yun\modules\admin\assets\AdminAsset;
    use yun\models\File;
    use yun\models\UserWildcard;
    use ucenter\models\District;
    use ucenter\models\Industry;
    use ucenter\models\Company;
    use ucenter\models\Department;
    use ucenter\models\Position;

$this->title = '【'.File::getFileFullRoute($dir->id).'】 - 权限设置';
AdminAsset::addJsFile($this,'js/main/dir/permission.js');
?>
<form id="permission-form" class="form-horizontal" action="" method="post">
<input type="hidden" id="dir_id" name="dir_id" value="<?=$dir->id?>" />
<table class="table table-bordered">
    <tr>
        <th width="60">#</th>
        <th width="120">文件属性限制</th>
        <th width="120">匹配类型</th>
        <th>匹配参数</th>
        <th width="120">操作类型</th>
        <th width="120">模式</th>
        <!--<th width="100">编辑</th>-->
    </tr>
<?php if(!empty($permission_list)):?>
    <?php foreach($permission_list as $dp):?>
        <?php if($dp->user_match_type==3){
            $user_wildcard = UserWildcard::find()->where(['id'=>$dp->user_match_param_id])->one();
        }?>
    <tr>
        <td>
            <?=$dp->id?>
        </td>
        <td>
            <?=BaseHtml::dropDownList('pm['.$dp->id.'][permission_type]',$dp->permission_type,DirPermission::getPermissionTypeItems(),['class'=>'permission-type-select'])?>
        </td>
        <td>
            <?=BaseHtml::dropDownList('pm['.$dp->id.'][user_match_type]',$dp->user_match_type,DirPermission::getTypeItems(),['class'=>'user-match-type-select'])?>
        </td>
        <td>
            <div class="user_match_param_div user_match_param_1_div" style="display: none;">
                --
                <?=BaseHtml::hiddenInput('pm['.$dp->id.'][user_match_param][1]',0)?>
            </div>

            <div class="user_match_param_div user_match_param_3_div" style="display: none;">
                地区: <?=BaseHtml::dropDownList('pm['.$dp->id.'][user_match_param][3][district_id]',$dp->user_match_type==3 && $user_wildcard?$user_wildcard->district_id:'',District::getItems())?> <br/>
                行业: <?=BaseHtml::dropDownList('pm['.$dp->id.'][user_match_param][3][industry_id]',$dp->user_match_type==3 && $user_wildcard?$user_wildcard->industry_id:'',Industry::getItems())?><br/>
                公司: <?=BaseHtml::dropDownList('pm['.$dp->id.'][user_match_param][3][company_id]',$dp->user_match_type==3 && $user_wildcard?$user_wildcard->company_id:'',Company::getItems())?><br/>
                部门: <?=BaseHtml::dropDownList('pm['.$dp->id.'][user_match_param][3][department_id]',$dp->user_match_type==3 && $user_wildcard?$user_wildcard->department_id:'',Department::getItems(),['encode'=>false])?><br/>
                职位: <?=BaseHtml::dropDownList('pm['.$dp->id.'][user_match_param][3][position_id]',$dp->user_match_type==3 && $user_wildcard?$user_wildcard->position_id:'',Position::getItems(),['encode'=>false])?>
            </div>

            <div class="user_match_param_div user_match_param_7_div" style="display: none;">
                用户组ID：<?=BaseHtml::textInput('pm['.$dp->id.'][user_match_param][7]',$dp->user_match_type==7?$dp->user_match_param_id:'')?>
            </div>
        </td>
        <td>
            <?=BaseHtml::dropDownList('pm['.$dp->id.'][operation]',$dp->operation,DirPermission::getOperationItems())?>

        </td>
        <td>
            <?=BaseHtml::dropDownList('pm['.$dp->id.'][mode]',$dp->mode,DirPermission::getModeItems())?>
<!--        </td>-->
<!--        <td>-->

            <?/*=BaseHtml::button('删除', ['class' => 'btn btn-danger btn-xs delete-btn', 'name' => 'delete-button']) */?>
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
    <?php endforeach;?>
<?php endif;?>



</table>
    <?=BaseHtml::button('保存', ['class' => 'btn btn-success save-btn', 'name' => 'save-button','style'=>'float:right;']) ?>
    <!--<div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary" name="login-button">提交</button>
        </div>
    </div>-->

</form>
