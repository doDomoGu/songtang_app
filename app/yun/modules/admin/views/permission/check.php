<?php
use yii\bootstrap\Html;
use yun\models\DirPermission;
    $this->title = '目录权限 - 权限检验';
    yun\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/permission/check.js');


function showStatus($bool){
    if($bool){
        echo '<span style="color:#16cfc2;" class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
    }else{
        echo '<span style="color:orangered;" class="glyphicon glyphicon-remove" aria-hidden="true"></span>';

    }
}
?>
<style>
    .form-label {
        width:120px;
        text-align: right;
        float:left;
        display: block;
        padding-top:4px;
    }
    .form-input {
        width:180px;
        float:left;
        display: block;
    }
    .form-error {
        width:280px;
        float:left;
        display: block;
        color:#c7000a;
        padding-top:4px;
    }
    .form-success {
        width:680px;
        float:left;
        display: block;
        color:#18a7a3;
        padding-top:4px;
    }
</style>
<section>
    <form action="" method="post">
        <input type="hidden" value="<?php echo Yii::$app->request->csrfToken; ?>" name="_csrf" >
        <div class="form-group clearfix">
            <span class="form-label">用户ID：</span>
            <span class="form-input">
            <input name="user_id" value="<?=$user_id?>" />
        </span>
            <span class="form-success <?=isset($successMsg['user'])?'':'hidden'?>">
            <?=isset($successMsg['user'])?$successMsg['user']:''?>
        </span>
            <span class="form-error <?=isset($errorMsg['user'])?'':'hidden'?>">
            <?=isset($errorMsg['user'])?$errorMsg['user']:''?>
        </span>
        </div>
        <div class="form-group clearfix">
            <span class="form-label">检验类型：</span>
            <span class="form-input">
                <?=Html::dropDownList('type',$type,[0=>'==请选择==',1=>'目录(系统)',2=>'文件夹(用户创建)',3=>"文件"],['class'=>'check-type'])?>
            </span>
            <span class="form-success <?=isset($successMsg['type'])?'':'hidden'?>">
                <?=isset($successMsg['type'])?$successMsg['type']:''?>
            </span>
            <span class="form-error <?=isset($errorMsg['type'])?'':'hidden'?>">
                <?=isset($errorMsg['type'])?$errorMsg['type']:''?>
            </span>
        </div>
        <div class="form-group dir_input clearfix" style="display: none;">
            <span class="form-label">目录ID：</span>
            <span class="form-input">
                <input name="dir_id" value="<?=$dir_id?>" />
            </span>
            <span class="form-success <?=isset($successMsg['dir'])?'':'hidden'?>">
                <?=isset($successMsg['dir'])?$successMsg['dir']:''?>
            </span>
            <span class="form-error <?=isset($errorMsg['dir'])?'':'hidden'?>">
                <?=isset($errorMsg['dir'])?$errorMsg['dir']:''?>
            </span>
        </div>
        <div class="form-group filedir_input clearfix" style="display: none;">
            <span class="form-label">文件夹ID：</span>
            <span class="form-input">
                <input name="filedir_id" value="<?=$filedir_id?>" />
            </span>
            <span class="form-success <?=isset($successMsg['filedir'])?'':'hidden'?>">
                <?=isset($successMsg['filedir'])?$successMsg['filedir']:''?>
            </span>
            <span class="form-error <?=isset($errorMsg['filedir'])?'':'hidden'?>">
                <?=isset($errorMsg['filedir'])?$errorMsg['filedir']:''?>
            </span>
        </div>
        <div class="form-group file_input clearfix" style="display: none;">
            <span class="form-label">文件ID：</span>
            <span class="form-input">
                <input name="file_id" value="<?=$file_id?>" />
            </span>
            <span class="form-success <?=isset($successMsg['file'])?'':'hidden'?>">
                <?=isset($successMsg['file'])?$successMsg['file']:''?>
            </span>
            <span class="form-error <?=isset($errorMsg['file'])?'':'hidden'?>">
                <?=isset($errorMsg['file'])?$errorMsg['file']:''?>
            </span>
        </div>
        <div class="form-group clearfix">
            <span class="form-label">&nbsp;</span>
            <span class="form-input"><button type="submit">提交</button></span>
        </div>
        <div class="result-div">
            <?php if(Yii::$app->request->getIsPost() && empty($errorMsg)):?>
                <?php
                $up1 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_NORMAL,DirPermission::OPERATION_UPLOAD,$user,true);
                $up2 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT,DirPermission::OPERATION_UPLOAD,$user,true);
                $up3 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_INDUSTRY,DirPermission::OPERATION_UPLOAD,$user,true);
                $up4 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT_INDUSTRY,DirPermission::OPERATION_UPLOAD,$user,true);
                $down1 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_NORMAL,DirPermission::OPERATION_DOWNLOAD,$user,true);
                $down2 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT,DirPermission::OPERATION_DOWNLOAD,$user,true);
                $down3 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_INDUSTRY,DirPermission::OPERATION_DOWNLOAD,$user,true);
                $down4 = DirPermission::isDirAllow($dir_id,DirPermission::PERMISSION_TYPE_ATTR_LIMIT_DISTRICT_INDUSTRY,DirPermission::OPERATION_DOWNLOAD,$user,true);
                ?>
                <table class="table table-bordered text-center">
                    <tr>
                        <td colspan="4">上传</td>
                        <td colspan="4">下载</td>
                    </tr>
                    <tr>
                        <td>全部</td>
                        <td>限制地区,不限制行业</td>
                        <td>限制行业,不限制地区</td>
                        <td>限制地区和行业</td>
                        <td>全部</td>
                        <td>限制地区,不限制行业</td>
                        <td>限制行业,不限制地区</td>
                        <td>限制地区和行业</td>
                    </tr>
                    <tr>
                        <td><?=showStatus($up1)?></td>
                        <td><?=showStatus($up2)?></td>
                        <td><?=showStatus($up3)?></td>
                        <td><?=showStatus($up4)?></td>
                        <td><?=showStatus($down1)?></td>
                        <td><?=showStatus($down2)?></td>
                        <td><?=showStatus($down3)?></td>
                        <td><?=showStatus($down4)?></td>
                    </tr>
                </table>
                <?php if($type==3):?>
                    <div style="color:#c7000a;">*上面表示文件所在的目录的权限</div>
                    <div>
                        文件的地区属性为： <?=$districtName?><Br/>
                        文件的行业属性为： <?=$industryName?><Br/>
                        文件是否可以被下载：  <?=showStatus(DirPermission::isFileAllow($dir_id,$file_id,DirPermission::OPERATION_DOWNLOAD,$user,true))?>
                    </div>
                <?php endif;?>
            <?php endif;?>
        </div>
        <!--<div class="error" style="color:#c7000a"><?/*=$errorMsg*/?></div>-->
    </form>

</section>

