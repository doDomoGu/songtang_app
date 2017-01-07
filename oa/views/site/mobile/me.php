<?php
    $user = Yii::$app->user->identity;
?>

<div class="weui-panel weui-panel_access">
    <!--<div class="weui-panel__hd">图文组合列表</div>-->
    <div class="weui-panel__bd">
        <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
            <div class="weui-media-box__hd">
                <img class="weui-media-box__thumb" src="/images/common/default-user.png" alt="" >
            </div>
            <div class="weui-media-box__bd">
                <h4 class="weui-media-box__title"><?=$user->name?></h4>
                <p class="weui-media-box__desc">***个人简介***</p>
            </div>
        </a>
    </div>
    <!--<div class="weui-panel__ft">
        <a href="javascript:void(0);" class="weui-cell weui-cell_access weui-cell_link">
            <div class="weui-cell__bd">查看更多</div>
            <span class="weui-cell__ft"></span>
        </a>
    </div>-->
</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p class="weui-media-box__desc" style="color:#333;">地区：<?=$user->area?></p>
        </div>
        <div class="weui-cell__ft"></div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p class="weui-media-box__desc" style="color:#333;">业态：<?=$user->business?></p>
        </div>
        <div class="weui-cell__ft"></div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p class="weui-media-box__desc" style="color:#333;">部门：<?=$user->department?></p>
        </div>
        <div class="weui-cell__ft"></div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p class="weui-media-box__desc" style="color:#333;">职位：<?=$user->position?></p>
        </div>
        <div class="weui-cell__ft"></div>
    </div>
</div>