<?php

$this->title = '我的申请';
//oa\assets\AppAsset::addJsFile($this,'js/main/apply/my.js');

?>
<div class="weui-cells">
<?php if(!empty($list)):?>
    <?php foreach($list as $l):?>
    <a class="weui-cell weui-cell_access" href="/apply/info?id=<?=$l->id?>">
        <div class="weui-cell__bd">
            <h4 class="weui-media-box__title"><?=$l->title?></h4>
            <p class="weui-media-box__desc" style="color:#333;">申请时间：<?=$l->add_time?></p>
            <p class="weui-media-box__desc"><?=$l->message?></p>
        </div>
        <div class="weui-cell__ft"></div>
    </a>
    <?php endforeach;?>
<?php else:?>
    <a class="weui-cell weui-cell_access" href="javascript:;">
    <div class="weui-cell__bd">
        <p style="color:#ff2222">无申请</p>
    </div>
    </a>
<?php endif;?>
</div>