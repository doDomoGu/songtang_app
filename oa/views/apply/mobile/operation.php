<?php
    use oa\assets\AppMobileAsset;
    use oa\models\Flow;
    $this->title = '事项处理';
    AppMobileAsset::addJsFile($this,'js/mobile/apply/operation.js');
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
<div class="weui-cells__title" style="color:#333;font-weight:bold;font-size:16px;">当前待处理流程</div>
<div class="weui-cells weui-cells_form">
    <input type="hidden" class="data-apply_id" value="<?=$apply->id?>" />
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">标题</label></div>
        <div class="weui-cell__bd">
            <?=$flow->title?>
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">操作类型</label></div>
        <div class="weui-cell__bd">
            <?=$flow->typeName?>
        </div>
    </div>
    <div class="weui-cell weui-cell_select weui-cell_select-after">
        <div class="weui-cell__hd">
            <label for="" class="weui-label">选择结果</label>
        </div>
        <div class="weui-cell__bd">
        <?php $items = Flow::getRadioItems($flow->type);?>
            <select class="weui-select data-result">
                <?php foreach($items as $k=>$v):?>
                <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__hd">
            <label for="" class="weui-label">备注信息</label>
        </div>
        <div class="weui-cell__bd">
            <textarea class="weui-textarea data-message" placeholder="请输入文本" rows="3"></textarea>

        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="submit-btn">确定</a>
    </div>
</div>
<div id='dialog-success' style="display: none;">
    <div class="weui-mask"></div>
    <div class="weui-dialog">
        <div class="weui-dialog__hd"><strong class="weui-dialog__title">提交成功</strong></div>
        <div class="weui-dialog__bd"></div>
        <div class="weui-dialog__ft">
            <a href="/apply/my" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
        </div>
    </div>
</div>

<div id="dialog-failure" style="display: none;">
    <div class="weui-mask"></div>
    <div class="weui-dialog">
        <div class="weui-dialog__hd"><strong class="weui-dialog__title">提交失败</strong></div>
        <div class="weui-dialog__bd">请重新检查表单内容</div>
        <div class="weui-dialog__ft">
            <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary">关闭</a>
        </div>
    </div>
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
