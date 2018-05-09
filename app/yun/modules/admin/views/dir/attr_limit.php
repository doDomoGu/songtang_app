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
    use yii\bootstrap\Html;

$this->title = '【'.File::getFileFullRoute($dir->id).'】 - 目录属性限制';
AdminAsset::addJsFile($this,'js/main/dir/attr-limit.js');

?>
<form id="attr-limit-form" class="form-horizontal" action="" method="post">
<input type="hidden" id="dir_id" name="dir_id" value="<?=$dir->id?>" />
<table class="table table-bordered">
    <tr>
        <td width="100" height="100">地区限制</td>
        <td>
            是否限制：<?=Html::dropDownList('isDistrictLimit',($districtArr===false?0:1),[0=>'不限制',1=>'限制'])?>
            <br/>
            <br/>
            <?=Html::checkboxList('districtCheck[]',$districtArr,District::getItems(true),['id'=>'districtCheck','style'=>'display:'.($districtArr===false?'none':'block')])?>
        </td>
    </tr>
    <tr>
        <td width="100" height="100">行业限制</td>
        <td>
            是否限制：<?=Html::dropDownList('isIndustryLimit',($industryArr===false?0:1),[0=>'不限制',1=>'限制'])?>
            <br/>
            <br/>
            <?=Html::checkboxList('industryCheck[]',$industryArr,Industry::getItems(true),['id'=>'industryCheck','style'=>'display:'.($industryArr===false?'none':'block')])?>
        </td>
    </tr>
</table>


    <?=BaseHtml::button('保存', ['class' => 'btn btn-success save-btn', 'name' => 'save-button','style'=>'float:right;']) ?>
    <!--<div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button type="submit" class="btn btn-primary" name="login-button">提交</button>
        </div>
    </div>-->

</form>
