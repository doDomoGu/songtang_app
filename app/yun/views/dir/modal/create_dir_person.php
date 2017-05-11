<?php
    use yii\bootstrap\Modal;
?>
<?php
Modal::begin([
    'header' => '新建文件夹(个人)',
    'id'=>'createDirPersonModal',
    /*'size'=>'modal-lg',*/
    'options'=>['style'=>'margin-top:120px;']
]);
?>
    <div id="createDirModalContent2">
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