<?php
    use oa\models\Apply;
    $this->title = '';
    $todoCount = Apply::getTodoList(true);
    $doneCount = Apply::getDoneList(true);
    $finishCount = Apply::getFinishList(true);
    $relatedCount = Apply::getRelatedList(true);
?>
<div class="weui-cells">
    <a class="weui-cell weui-cell_access" href="/apply/todo">
        <div class="weui-cell__bd">
            <p>待办事项</p>
        </div>
        <div class="weui-cell__ft"><?=$todoCount>0?$todoCount:''?> &nbsp;</div>
    </a>
</div>

<div class="weui-cells">
    <a class="weui-cell weui-cell_access" href="/apply/done">
        <div class="weui-cell__bd">
            <p>办结事项</p>
        </div>
        <div class="weui-cell__ft"><?=$doneCount>0?$doneCount:''?> &nbsp;</div>
    </a>
</div>

<div class="weui-cells">
    <a class="weui-cell weui-cell_access" href="/apply/finish">
        <div class="weui-cell__bd">
            <p>完结事项</p>
        </div>
        <div class="weui-cell__ft"><?=$finishCount>0?$finishCount:''?> &nbsp;</div>
    </a>
</div>

<div class="weui-cells">
    <a class="weui-cell weui-cell_access" href="/apply/related">
        <div class="weui-cell__bd">
            <p>相关事项</p>
        </div>
        <div class="weui-cell__ft"><?=$relatedCount>0?$relatedCount:''?> &nbsp;</div>
    </a>
</div>
<br/>
<br/>
<article class="weui-article" style="background: #ccc">
    <section>
        <h2 class="title">事项的定义与解释</h2>
        <section>
            <p>
                首先事项的意思是一个OA流程中某个环节你是操作人则是与你相关的一个事项。<br/>
                <b>待办事项</b> 当前流程走到了你是操作人的环节<br/>
                <b>办结事项</b> 当前流程之后的任何环节操作人都不是你了<br/>
                <b>完结事项</b> 流程状态为已完结<br/>
                <b>相关事项</b> 除了上述流程之外的与你相关的事项<br/>
            </p>
        </section>
    </section>
</article>
