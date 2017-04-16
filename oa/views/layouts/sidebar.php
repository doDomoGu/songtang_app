<?php
    $user = Yii::$app->user->identity;
?>

<section id="sidebar" class="well well-sm">
    <div class="sidebar-head" >
        职员信息
    </div>
    <ul class="sidebar-ul list-unstyled">
        <li>
            <span class="col">姓名：</span>
            <span class="val"><?=$user->name?></span>
        </li>
        <li>
            <span class="col">地区：</span>
            <span class="val"><?=$user->district?></span>
        </li>
        <li>
            <span class="col">行业：</span>
            <span class="val"><?=$user->industry?></span>
        </li>
        <li>
            <span class="col">公司：</span>
            <span class="val"><?=$user->company?></span>
        </li>
        <li>
            <span class="col">部门：</span>
            <span class="val"><?=$user->department?></span>
        </li>
        <li>
            <span class="col">职位：</span>
            <span class="val"><?=$user->position?></span>
        </li>
    </ul>
</section>