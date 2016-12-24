<?php
    use yii\bootstrap\Modal;
    app\assets\AppAsset::addJsFile($this,'js/main/dir/modal/edit.js');
?>
<?php
Modal::begin([
    'header' => '修改文件/文件夹名',
    'id'=>'editModal',
    /*'size'=>'modal-lg',*/
    'options'=>['style'=>'margin-top:120px;']
]);
?>
    <div id="editModalContent">
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