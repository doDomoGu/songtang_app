<?php
    use yii\bootstrap\Modal;
use yii\bootstrap\Progress;
?>
<?php
Modal::begin([
    'header' => '上传文件(个人)',
    'id'=>'uploadPersonModal',
    /*'size'=>'modal-lg',*/
    'options'=>['style'=>'margin-top:120px;']
]);
?>
    <div id="uploadModalContent2">
        <div id="pickfile2_container">
            <p>
                <input type="file" id="pickfile2">
            </p>
            <p>
                <input type="hidden" id="fileurl2" name="fileurl" value="" class="col-lg-6" />
            </p>
            <div class="alert alert-danger" role="alert">
                *上传文件较大时，进度条完成前，请勿操作当前页面
            </div>
            <div class="clearfix" id="fileurl2_upload_txt"></div>
            <?php            echo Progress::widget([
                'percent' => 0,
                'label' => '上传中',
                'id'=>'upload-progress-2',
                'options'=>['class' => 'progress-striped active','style'=>'display:none;']
            ]);
            ?>
        </div>
    </div>
<?php
Modal::end();
?>