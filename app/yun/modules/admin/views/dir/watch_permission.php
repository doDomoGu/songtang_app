<?php
    use yii\bootstrap\BaseHtml;
    use yun\models\DirPermission;
    use ucenter\models\User;
    use ucenter\models\District;
    use ucenter\models\Industry;
    use ucenter\models\Company;
    use ucenter\models\Department;
    use ucenter\models\Position;
    use yun\modules\admin\assets\AdminAsset;
    use yun\models\File;
    use common\components\CommonFunc;

$this->title = '【'.File::getFileFullRoute($dir->id).'】 - 权限查看 (职员版）';
function showStatus($bool){
    if($bool){
        echo '<span style="color:#77cc77;" class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
    }else{
        echo '<span style="color:orangered;" class="glyphicon glyphicon-remove" aria-hidden="true"></span>';

    }
}

//AdminAsset::addJsFile($this,'js/main/dir/permission.js');
?>
<p>
    *四个权限分类依次为 全部/限制地区/限制行业/限制地区及行业
</p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th width="60" rowspan="2">#</th>
        <th width="60"  rowspan="2">姓名</th>
        <th rowspan="2">职位</th>
        <!--<th class="text-center">上传</th>-->
        <th class="text-center" colspan="4">上传*</th>
        <!--<th class="text-center">下载</th>-->
        <th class="text-center" colspan="4">下载*</th>

        <!--<th>类型</th>-->
        <!--<th>操作</th>-->
    </tr>
    <tr>
        <th class="text-center">全部</th>
        <th class="text-center">限制地区</th>
        <th class="text-center">限制行业</th>
        <th class="text-center">限制地区及行业</th>
        <th class="text-center">全部</th>
        <th class="text-center">限制地区</th>
        <th class="text-center">限制行业</th>
        <th class="text-center">限制地区及行业</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($userList as $u):?>
        <tr>
            <th scope="row"><?=$u->id?></th>
            <td><?=$u->name?></td>
            <td>
                <?=CommonFunc::getByCache(District::className(),'getName',[$u->district_id],'ucenter:district/name')?> >
                <?=CommonFunc::getByCache(Industry::className(),'getName',[$u->industry_id],'ucenter:industry/name')?> >
                <?=CommonFunc::getByCache(Company::className(), 'getName',[$u->company_id], 'ucenter:company/name')?> >
                <?=CommonFunc::getByCache(Department::className(),'getFullRoute',[$u->department_id],'ucenter:department/full-route')?> >
                <?=CommonFunc::getByCache(Position::className(),'getName',[$u->position_id],'ucenter:position/name')?>
                <?/*=$u->getFullPositionRoute()*/?>
            </td>
            <?php
                $up1All = DirPermission::isDirAllow($dir->id,DirPermission::PERMISSION_TYPE_NORMAL,DirPermission::OPERATION_UPLOAD,$u,true);
                $up1District = DirPermission::isDirAllow($dir->id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT,DirPermission::OPERATION_UPLOAD,$u,true);
                $up1Industry = DirPermission::isDirAllow($dir->id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_INDUSTRY,DirPermission::OPERATION_UPLOAD,$u,true);
                $up1DisInd = DirPermission::isDirAllow($dir->id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT_INDUSTRY,DirPermission::OPERATION_UPLOAD,$u,true);
                /*$up2All = $up1All;
                $up2District = $up1All || $up1District;
                $up2Industry = $up1All || $up1Industry;
                $up2DisInd = $up1All || $up1District || $up1Industry || $up1DisInd;*/


                $down1All = DirPermission::isDirAllow($dir->id,DirPermission::PERMISSION_TYPE_NORMAL,DirPermission::OPERATION_DOWNLOAD,$u,true);
                $down1District = DirPermission::isDirAllow($dir->id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT,DirPermission::OPERATION_DOWNLOAD,$u,true);
                $down1Industry = DirPermission::isDirAllow($dir->id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_INDUSTRY,DirPermission::OPERATION_DOWNLOAD,$u,true);
                $down1DisInd = DirPermission::isDirAllow($dir->id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT_INDUSTRY,DirPermission::OPERATION_DOWNLOAD,$u,true);
                /*$down2All = $down1All;
                $down2District = $down1All || $down1District;
                $down2Industry = $down1All || $down1Industry;
                $down2DisInd = $down1All || $down1District || $down1Industry || $down1DisInd;*/

            ?>
            <td class="text-center">
                <?=showStatus($up1All)?>
            </td>
            <td class="text-center">
                <?=showStatus($up1District)?>
            </td>
            <td class="text-center">
                <?=showStatus($up1Industry)?>
            </td>
            <td class="text-center">
                <?=showStatus($up1DisInd)?>
            </td>
            <!--<td width="120" class="text-center">
                <?/*=showStatus($up2All)*/?>/<?/*=showStatus($up2District)*/?>/<?/*=showStatus($up2Industry)*/?>/<?/*=showStatus($up2DisInd)*/?>
            </td>-->
            <td width="120" class="text-center">
                <?=showStatus($down1All)?>
            </td>
            <td class="text-center">
                <?=showStatus($down1District)?>
            </td>
            <td class="text-center">
                <?=showStatus($down1Industry)?>
            </td>
            <td class="text-center">
                <?=showStatus($down1DisInd)?>
            </td>
            <!--<td width="120" class="text-center">
                <?/*=showStatus($down2All)*/?>/<?/*=showStatus($down2District)*/?>/<?/*=showStatus($down2Industry)*/?>/<?/*=showStatus($down2DisInd)*/?>
            </td>-->


            <!--<td>
                <?/*=BaseHtml::a('编辑',['add-and-edit','id'=>$l->id],['class'=>'btn btn-primary btn-xs'])*/?>
                <?php /*if($l->link==""):*/?>
                    <?/*=BaseHtml::a('权限设置',['permission','dir_id'=>$l->id],['class'=>'btn btn-success btn-xs disabled'])*/?>

                    <?php /*if($l->is_leaf==0):*/?>
                        <?/*=BaseHtml::a('添加子目录',['add-and-edit','p_id'=>$l->id],['class'=>'btn btn-warning btn-xs disabled'])*/?>
                    <?php /*else:*/?>

                    <?php /*endif;*/?>
                <?php /*endif;*/?>
            </td>-->
        </tr>


    <?php endforeach;?>
    </tbody>
</table>
<?php /*if(!empty($dir->dirPermission)):*/?><!--
    <?php /*$i=1;foreach($dir->dirPermission as $dp):*/?>
    <div class="form-group permission-one" style="background: #d1d1d1;padding:10px 0;">
        <label class="col-lg-2 control-label"><?/*=$i*/?></label>
        <div class="col-lg-6">
            <div>
                匹配人员类型：
                <?/*=BaseHtml::dropDownList('type_'.$i,$dp->type,DirPermission::getTypeItems(),['class'=>'type-select'])*/?>
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
    </div>
    <?php /*$i++;endforeach;*/?>
--><?php /*endif;*/?>

