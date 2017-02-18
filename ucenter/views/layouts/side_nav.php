<?php
    //手动引入bootstrap.js
    //**由于有可能没有调用任何bootstrap组件   **使用Asset依赖注册不会重复引入js文件
    yii\bootstrap\BootstrapPluginAsset::register($this);
?>
<div class="side-nav">
    <div class="side-head">
        颂唐职员管理系统
    </div>
    <ul class="nav nav-pills nav-stacked">
        <li class="menu-single <?=$this->context->id=='site'?'active':''?>">
            <a href="/">
                <span class="menu-icon glyphicon glyphicon-home"></span>
                仪表盘
            </a>
        </li>
        <li class="menu-single <?=$this->context->id=='user'?'active':''?>">
            <a href="/user">
                <span class="menu-icon glyphicon glyphicon-user"></span>
                职员管理
            </a>
        </li>
        <li class="menu-single <?=$this->context->id=='structure'?'active':''?>">
            <a href="/structure">
                <span class="menu-icon glyphicon glyphicon-list"></span>
                组织结构
            </a>
        </li>
        <li class="menu-list <?=$this->context->id=='setting'?'nav-active':''?>">
            <a href="javascript:void(0);" class="<?=$this->context->id=='setting'?'':'collapsed'?>">
                <span class="menu-icon glyphicon glyphicon-cog"></span>
                参数设置
                <span class="sub-menu-collapsed glyphicon glyphicon-plus"></span>
                <span class="sub-menu-collapsed-in glyphicon glyphicon-minus"></span>
            </a>
            
            <ul class="sub-menu-list collapse <?=$this->context->id=='setting'?'in':''?>" id="system-collapse">
                <li class="<?=$this->context->id=='setting' && $this->context->action->id=='district'?'active':''?>">
                    <a href="/setting/district">
                        地区
                    </a>
                </li>
                <li class="<?=$this->context->id=='setting' && $this->context->action->id=='industry'?'active':''?>">
                    <a href="/setting/industry">
                        行业
                    </a>
                </li>
                <li class="<?=$this->context->id=='setting' && $this->context->action->id=='company'?'active':''?>">
                    <a href="/setting/company">
                        公司
                    </a>
                </li>
                <li class="<?=$this->context->id=='setting' && $this->context->action->id=='department'?'active':''?>">
                    <a href="/setting/department">
                        部门
                    </a>
                </li>
                <li class="<?=$this->context->id=='setting' && $this->context->action->id=='position'?'active':''?>">
                    <a href="/setting/position">
                        职位
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-single <?=$this->context->id=='user-app-auth'?'active':''?>">
            <a href="/user-app-auth">
                <span class="menu-icon glyphicon glyphicon-credit-card"></span>
                用户应用权限
            </a>
        </li>
        <!--<li class="menu-list <?/*=$this->context->id=='pos-setting'?'nav-active':''*/?>">
            <a href="javascript:void(0);" class="<?/*=$this->context->id=='pos-setting'?'':'collapsed'*/?>">
                <span class="menu-icon glyphicon glyphicon-cog"></span>
                系统设置
                <span class="sub-menu-collapsed glyphicon glyphicon-plus"></span>
                <span class="sub-menu-collapsed-in glyphicon glyphicon-minus"></span>
            </a>
            <ul class="sub-menu-list collapse <?/*=$this->context->id=='pos-setting'?'in':''*/?>" id="system-collapse">
                <li class="<?/*=$this->context->id=='pos-setting' && $this->context->action->id=='index'?'active':''*/?>">
                    <a href="/sys-setting/admin">
                        管理员
                    </a>
                </li>
            </ul>
        </li>-->

        <li class="menu-single">
            <a href="<?=Yii::$app->params['logoutUrl']?>">
                <span class="menu-icon glyphicon glyphicon-log-out"></span>
                退出
            </a>
        </li>
    </ul>
</div>
