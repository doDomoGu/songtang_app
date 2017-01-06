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
            <h4 class="weui-media-box__title"><?=$l->title?></h4>
            <p class="weui-media-box__desc" style="color:#333;">##显示过去了多少时间##</p>
            <p class="weui-media-box__desc"><?=$l->message?></p>
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