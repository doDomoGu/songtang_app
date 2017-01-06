<?php
    use oa\assets\AppMobileAsset;
    $this->title = '发起申请';
    $areaItems = \ucenter\models\Area::getItems();
    $businessItems = \ucenter\models\Business::getItems();

    AppMobileAsset::addJsFile($this,'js/mobile/apply/create.js');
?>
<div class="weui-cells weui-cells_form">
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">申请标题</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input data-title" type="text" placeholder="请输入标题" />
        </div>
    </div>
    <div class="weui-cell weui-cell_select weui-cell_select-after">
        <div class="weui-cell__hd">
            <label for="" class="weui-label">地区</label>
        </div>
        <div class="weui-cell__bd">
            <select class="weui-select data-area_id">
                <option value="">==请选择==</option>
                <?php foreach($areaItems as $k=>$t):?>
                    <option value="<?=$k?>"><?=$t?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="weui-cell weui-cell_select weui-cell_select-after">
        <div class="weui-cell__hd">
            <label for="" class="weui-label">业态</label>
        </div>
        <div class="weui-cell__bd">
            <select class="weui-select data-business_id">
                <option value="">==请选择==</option>
                <?php foreach($businessItems as $k=>$t):?>
                    <option value="<?=$k?>"><?=$t?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="weui-cell weui-cell_select weui-cell_select-after">
        <div class="weui-cell__hd">
            <label for="" class="weui-label">任务模板</label>
        </div>
        <div class="weui-cell__bd">
            <select class="weui-select data-task_id">
                <option value="">==请选择==</option>
            </select>
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__hd">
            <label for="" class="weui-label">申请信息</label>
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