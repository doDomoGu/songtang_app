<?php
    use yii\bootstrap\Modal;
    use yii\bootstrap\Html;
    use ucenter\models\District;
    use ucenter\models\Industry;

    yun\assets\AppAsset::addJsFile($this,'js/main/dir/modal/edit.js');

    $districtItems = District::getItems(true);
    $industryItems = Industry::getItems(true);
?>
<?php
Modal::begin([
    'header' => '修改文件/文件夹',
    'id'=>'editModal',
    /*'size'=>'modal-lg',*/
    'options'=>['style'=>'margin-top:120px;']
]);
?>

    <div id="editModalContent">
        <input type="hidden" class="edit_is_dir" />
        <p>
            地区：<?=Html::dropDownList('district-check','',$districtItems,['class'=>'attr-check district-check'])?>
        </p>
        <p>
            行业：<?=Html::dropDownList('industry-check','',$industryItems,['class'=>'attr-check industry-check'])?>
        </p>
        <p>
            <label>文件/文件夹名（旧）：</label>
            <span class="filename_old"></span>
        </p>
        <p>
            <label>文件/文件夹名（新）：</label>
            <input class="filename_new" name="filename_new" >
            <span class="edit-error"></span>
        </p>
        <p>
            <button class="btn btn-success">提交</button>
        </p>
        <input type="hidden" class="file_id" />
    </div>
<?php
Modal::end();
?>