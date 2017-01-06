<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;

$this->title = '待办事项';
//oa\assets\AppAsset::addJsFile($this,'js/main/apply/my.js');

?>
<div class="weui-cells">
<?php if(!empty($list)):?>
    <?php foreach($list as $l):?>
    <a class="weui-cell weui-cell_access" href="/apply/operation?id=<?=$l->id?>">
        <div class="weui-cell__bd">
            <p><h4 class="weui-media-box__title"><?=$l->title?></h4>
            <p class="weui-media-box__desc"><?=$l->message?></p></p>
        </div>
        <div class="weui-cell__ft"></div>
    </a>
    <?php endforeach;?>
<?php else:?>
    <a class="weui-cell weui-cell_access" href="javascript:;">
    <div class="weui-cell__bd">
        <p style="color:#ff2222">无待办事项</p>
    </div>
    </a>
<?php endif;?>
</div>
<?php
/*Modal::begin([
    'header' => '申请表详情',
    'id'=>'infoModal',
    //'size'=>'modal-lg',
    'options'=>['style'=>'margin-top:120px;'],
]);
*/?><!--
    <div id="infoContent">
        <div class="content"></div>
        <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
    </div>
--><?php
/*Modal::end();
*/?>