<?php
    use yii\bootstrap\Modal;
    use ucenter\models\Area;
    use ucenter\models\Business;
    use yii\helpers\BaseHtml;
    yun\assets\AppAsset::addJsFile($this,'js/main/dir/modal/create_dir_common.js');

    $areaItems = Area::getItems(true);
    $businessItems = Business::getItems(true);
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
            地区：<?=BaseHtml::dropDownList('area-check','',$areaItems,['class'=>'attr-check area-check'])?>
        </p>
        <p style="display: none;">
            业态：<?=BaseHtml::dropDownList('business-check','',$businessItems,['class'=>'attr-check business-check'])?>
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