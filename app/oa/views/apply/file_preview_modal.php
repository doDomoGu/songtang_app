<?php
use yii\bootstrap\Modal;

//yun\assets\AppAsset::addJsFile($this,'js/main/dir/modal/edit.js');


?>
<?php
Modal::begin([
    'header' => '文件预览',
    'id'=>'file_preview_modal',
    'size'=>'modal-lg',
    //'options'=>['style'=>'margin-top:120px;']
]);
?>

    <iframe id='file_preview_iframe' width=100% height=100% frameborder=0 scrolling=auto src=''></iframe>
<?php
Modal::end();
?>