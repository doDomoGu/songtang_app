<?php
    use yii\bootstrap\Modal;
    app\assets\AppAsset::addJsFile($this,'js/main/dir/modal/create_dir_common.js');
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