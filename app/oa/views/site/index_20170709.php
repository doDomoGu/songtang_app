<?php
use oa\models\TaskCategory;
use yii\helpers\Html;
$this->title = '';

$categoryType = TaskCategory::getTypeList();
oa\assets\AppAsset::addCssFile($this,'css/main/site/index.css');
?>
<section id="task-index">
    <div class="task-content">
        <div class="task-list">
            <span class="task-title task-title-1">
                <img src="/images/main/site/index_tag_1.png" />
            </span>
            <div class="task-category-list ul-1">
                <ul>
                    <li><a href="/apply/create?category=1">招聘</a></li>
                    <li><a href="/apply/create?category=2">入职</a></li>
                    <li><a href="/apply/create?category=3">转正</a></li>
                    <li><a href="/apply/create?category=8">解聘</a></li>
                    <li><a href="/apply/create?category=7">离职</a></li>
                    <li><a href="/apply/create?category=4">调职</a></li>
                    <li><a href="/apply/create?category=5">调薪</a></li>
                    <li><a href="/apply/create?category=6">调动</a></li>
                </ul>
                <ul>
                    <li><a href="/apply/create?category=25">请假</a></li>
                    <li><a href="/apply/create?category=9">调休</a></li>
                    <li><a href="/apply/create?category=11">加班</a></li>
                    <li><a href="/apply/create?category=26">外出</a></li>
                    <li><a href="/apply/create?category=10">出差</a></li>
                </ul>
            </div>
        </div>
        <div class="task-list">
            <span class="task-title task-title-2">
                <img src="/images/main/site/index_tag_2.png" />
            </span>
            <div class="task-category-list ul-2">
                <li><a href="/apply/create?category=12">备用金申请</a></li>
                <li><a href="/apply/create?category=13">报销申请</a></li>
                <li><a href="/apply/create?category=14">奖金申请</a></li>
                <li><a href="/apply/create?category=15">奖励申请</a></li>
                <li><a href="/apply/create?category=16">其他费用</a></li>
            </div>
        </div>
        <div class="task-list">
            <span class="task-title task-title-3">
                <img src="/images/main/site/index_tag_3.png" />
            </span>
            <div class="task-category-list ul-3">
                <li><a href="/apply/create?category=17">公章</a></li>
                <li><a href="/apply/create?category=19">法人章</a></li>
                <li><a href="/apply/create?category=27">合同章</a></li>
                <li><a href="/apply/create?category=18">证照</a></li>
            </div>
        </div>
        <div class="task-list">
            <span class="task-title task-title-4">
                <img src="/images/main/site/index_tag_4.png" />
            </span>
            <div class="task-category-list ul-4">
                <li><a href="/apply/create?category=20">固定资产采购</a></li>
                <li><a href="/apply/create?category=21">办公用品采购</a></li>
                <li><a href="/apply/create?category=22">物料采购</a></li>
                <li><a href="/apply/create?category=24">事项审批</a></li>
            </div>
        </div>
    </div>
</section>
