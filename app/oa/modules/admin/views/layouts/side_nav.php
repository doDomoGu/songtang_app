<?php
    use oa\modules\admin\components\AdminFunc;
    //手动引入bootstrap.js
    //**由于有可能没有调用任何bootstrap组件   **使用Asset依赖注册不会重复引入js文件
    yii\bootstrap\BootstrapPluginAsset::register($this);
?>
<div class="side-nav">
    <div class="side-head">
        颂唐OA后台管理
    </div>
    <ul class="nav nav-pills nav-stacked">
        <li class="menu-single <?=$this->context->id=='default'?'active':''?>">
            <a href="<?=AdminFunc::adminUrl('/')?>">
                <span class="menu-icon glyphicon glyphicon-home"></span>
                仪表盘
            </a>
        </li>
        <li class="menu-single <?=$this->context->id=='apply'?'active':''?>">
            <a href="<?=AdminFunc::adminUrl('apply')?>">
                <span class="menu-icon glyphicon glyphicon-list"></span>
                申请列表
            </a>
        </li>
        <li class="menu-list <?=$this->context->id=='task'?'nav-active':''?>">
            <a href="javascript:void(0);" class="<?=$this->context->id=='task'?'':'collapsed'?>">
                <span class="menu-icon glyphicon glyphicon-cog"></span>
                模板设置
                <span class="sub-menu-collapsed glyphicon glyphicon-plus"></span>
                <span class="sub-menu-collapsed-in glyphicon glyphicon-minus"></span>
            </a>

            <ul class="sub-menu-list collapse <?=$this->context->id=='task'?'in':''?>" id="system-collapse">
                <li class="<?=$this->context->id=='task' && $this->context->action->id=='index'?'active':''?>">
                    <a href="<?=AdminFunc::adminUrl('task')?>">
                        模板
                    </a>
                </li>
                <li class="<?=$this->context->id=='task' && $this->context->action->id=='category'?'active':''?>">
                    <a href="<?=AdminFunc::adminUrl('task/category')?>">
                        分类
                    </a>
                </li>
                <li class="<?=$this->context->id=='task' && $this->context->action->id=='form'?'active':''?>">
                    <a href="<?=AdminFunc::adminUrl('task/form')?>">
                        表单
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-single">
            <a href="<?=Yii::$app->params['logoutUrl']?>">
                <span class="menu-icon glyphicon glyphicon-log-out"></span>
                退出
            </a>
        </li>
    </ul>
</div>
