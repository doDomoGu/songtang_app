<?php
    use yii\bootstrap\Modal;
    use yii\bootstrap\Progress;
    use ucenter\models\Area;
    use ucenter\models\Business;
    use yii\bootstrap\BaseHtml;

    yun\assets\AppAsset::addJsFile($this,'js/qiniu/plupload.full.min.js');
    yun\assets\AppAsset::addJsFile($this,'js/qiniu/qiniu.js');
    yun\assets\AppAsset::addJsFile($this,'js/main/dir/modal/upload_common.js');

    $areaItems = Area::getItems(true);
    $businessItems = Business::getItems(true);
?>
<?php
Modal::begin([
    'header' => '上传文件',
    'id'=>'uploadCommonModal',
    'size'=>'modal-lg',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="uploadModalContent">
        <div id="pickfile_container">
            <p>
                地区：<?=BaseHtml::dropDownList('area-check','',$areaItems,['class'=>'attr-check area-check'])?>
            </p>
            <p>
                业态：<?=BaseHtml::dropDownList('business-check','',$businessItems,['class'=>'attr-check business-check'])?>
            </p>
            <p>
                <input type="file" id="pickfile">
            </p>
            <p>
                <input type="hidden" id="fileurl" name="fileurl" value="" class="col-lg-6" />
            </p>
            <div class="alert alert-danger" role="alert">
                * 上传文件较大时，请耐心等待进度条完成，请勿操作当前页面
                <br/>* 一旦上传列表中有一个文件的文件名已存在，此次上传会被立刻取消
            </div>
            <div class="clearfix" id="fileurl_upload_txt"></div>
            <div id="upload_progress" style="display:non33e;">

            </div>

        </div>
    </div>

<?php
Modal::end();
?>