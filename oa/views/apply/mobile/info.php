<?php
    use oa\models\Flow;
    $this->title = '申请详情';
?>
<div class="weui-cells__title">发起申请 <b style="color:#333;"><?=$apply->title?></b></div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <p class="weui-media-box__desc">操作人：<b><?=$apply->applyUser->name?></b> 时间：<b><?=$apply->add_time?></b></p>
            <p class="weui-media-box__desc">申请信息：<b><?=$apply->message?></b></p>
        </div>
        <div class="weui-cell__ft"></div>
    </div>
</div>
<div class="weui-cells__title">已处理流程</div>
<div class="weui-cells">
    <?php if(!empty($records)):$i=1;?>
        <?php foreach($records as $l):?>
            <div class="weui-cell" >
                <div class="weui-cell__bd">
                    <p class="weui-media-box__desc">标题：<b><?=$l->flow->title?></b>  操作类型：<b><?=$l->flow->typeName?></b></p>
                    <p class="weui-media-box__desc">操作人：<b><?=$l->flow->user->name?></b> 时间: <b><?=$l->add_time?></b> </p>
                    <p class="weui-media-box__desc">结果：<b><?=Flow::getResultCn($l->flow->type,$l->result)?></b></p>
                    <p class="weui-media-box__desc">备注信息：<b><?=$l->message?></b></p>
                </div>
                <div class="weui-cell__ft"></div>
            </div>
        <?php $i++;endforeach;?>
    <?php endif;?>
</div>
<div class="weui-cells__title">未处理流程</div>
<div class="weui-cells">
    <?php if(!empty($flowNotDo)):?>
        <?php foreach($flowNotDo as $l):?>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p class="weui-media-box__desc">标题：<b><?=$l->title?></b>  操作类型：<b><?=$l->typeName?></b></p>
                    <p class="weui-media-box__desc">操作人：<b><?=$l->user->name?></b> </p>
                </div>
                <div class="weui-cell__ft"></div>
            </div>
        <?php endforeach;?>
    <?php endif;?>
</div>

<div id='dialog-operation-success' style="display: none;">
    <div class="weui-mask"></div>
    <div class="weui-dialog">
        <div class="weui-dialog__hd"><strong class="weui-dialog__title">操作成功</strong></div>
        <div class="weui-dialog__bd"></div>
        <div class="weui-dialog__ft">
            <a href="/apply/todo" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
        </div>
    </div>
</div>

<div id="dialog-operation-failure" style="display: none;">
    <div class="weui-mask"></div>
    <div class="weui-dialog">
        <div class="weui-dialog__hd"><strong class="weui-dialog__title">操作失败</strong></div>
        <div class="weui-dialog__bd">请重新检查表单内容</div>
        <div class="weui-dialog__ft">
            <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">关闭</a>
        </div>
    </div>
</div>
