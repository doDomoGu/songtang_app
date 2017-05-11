<?php
    use yii\bootstrap\Modal;
    use ucenter\models\District;
    use ucenter\models\Industry;
    use yii\helpers\BaseHtml;
    yun\assets\AppAsset::addJsFile($this,'js/main/dir/modal/create_dir_common.js');

    $districtItems = District::getItems(true);
    $industryItems = Industry::getItems(true);
?>
<?php
Modal::begin([
    'header' => '新建文件夹',
    'id'=>'createDirCommonModal',
    /*'size'=>'modal-lg',*/
    'options'=>['style'=>'margin-top:120px;']
]);
?>
    <div id="createDirModalContent">
        <p style="display: none;">
            地区：<?=BaseHtml::dropDownList('district-check','',$districtItems,['class'=>'attr-check district-check'])?>
        </p>
        <p style="display: none;">
            行业：<?=BaseHtml::dropDownList('industry-check','',$industryItems,['class'=>'attr-check industry-check'])?>
        </p>
        <p>
            <label>文件夹名：</label>
            <input name="dirname" class="dirname">
            <span class="create-dir-error"></span>
        </p>
        <p>
            <button class="btn btn-success">提交</button>
        </p>
    </div>
<?php
Modal::end();
?>