<?php
use yii\bootstrap\Html;

    $this->title = '目录权限 - 检测';
    yun\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/permission/check.js');
?>
<style>
    .form-label {
        width:120px;
        text-align: right;
        float:left;
        display: block;
    }
    .form-input {
        width:220px;
        float:left;
        display: block;
    }
</style>
<section>
    <form action="" method="get">
    <div class="clearfix">
        <span class="form-label">类型：</span>
        <span class="form-input"><?=Html::dropDownList('type',$type,[0=>'==请选择==',1=>'目录',2=>'文件夹'],['class'=>'check-type'])?></span>
    </div>
    <div class="dir_input clearfix">
        <span class="form-label">目录ID：</span>
        <span class="form-input"><input name="dir_id" value="<?=$dir_id?>" /></span>
    </div>
    <div class="filedir_input clearfix">
        <span class="form-label">文件夹ID：</span>
        <span class="form-input"><input name="p_id" value="<?=$p_id?>" /></span>
    </div>
    <div class="clearfix">
        <span class="form-label">用户ID：</span>
        <span class="form-input"><input name="user_id" value="<?=$user_id?>" /></span>
    </div>
    <div class="clearfix">
        <span class="form-label"></span>
        <span class="form-input"><button type="submit">提交</button></span>
    </div>
        <div class="error" style="color:#c7000a"><?=$errorMsg?></div>
    </form>

</section>

