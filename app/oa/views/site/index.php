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
                    <li><a href="/apply/create?category=2">录用</a></li>
                    <li><a href="/apply/create?category=3">转正</a></li>
                    <li><a href="/apply/create?category=7">离职</a></li>
                    <li><a href="/apply/create?category=8">异动</a></li>
                    <li><a href="/apply/create?category=25">请假</a></li>
                    <li class="last-line"><a href="/apply/create?category=11">加班</a></li>
                    <li class="last-line word-line-2"><a href="/apply/create?category=10">外出<br/>出差</a></li>
                    <li class="last-line word-line-3"><a href="/apply/create?category=26">劳动<br/>合同<br/>续签</a></li>
                </ul>
            </div>
        </div>
        <div class="task-list">
            <span class="task-title task-title-2">
                <img src="/images/main/site/index_tag_2.png" />
            </span>
            <div class="task-category-list ul-2">
                <ul>
                    <li class="word-line-2"><a href="/apply/create?category=12">日常<br/>报销</a></li>
                    <li class="word-line-2"><a href="/apply/create?category=13">差旅<br/>报销</a></li>
                    <li class="word-line-2"><a href="/apply/create?category=14">对公<br/>付款</a></li>
                    <li class="word-line-2"><a href="/apply/create?category=15">开票<br/>申请</a></li>
                </ul>
            </div>
        </div>
        <div class="task-list">
            <span class="task-title task-title-3">
                <img src="/images/main/site/index_tag_3.png" />
            </span>
            <div class="task-category-list ul-3">
                <ul>
                    <li><a href="/apply/create?category=17">公章</a></li>
                    <li class="word-num-3"><a href="/apply/create?category=19">法人章</a></li>
                    <li class="word-num-3"><a href="/apply/create?category=27">合同章</a></li>
                    <li><a href="/apply/create?category=18">证照</a></li>
                    <li class="word-line-2 last-line"><a href="/apply/create?category=28">印章<br/>制作</a></li>
                    <li class="word-line-2 last-line"><a href="/apply/create?category=29">印章<br/>异常</a></li>
                </ul>
            </div>
        </div>
        <div class="task-list">
            <span class="task-title task-title-4">
                <img src="/images/main/site/index_tag_4.png" />
            </span>
            <div class="task-category-list ul-4">
                <ul>
                    <li><a href="/apply/create?category=20">采购</a></li>
                    <li class="word-line-2"><a href="/apply/create?category=22">名片<br/>胸牌</a></li>
                    <li class="last-line word-line-2"><a href="/apply/create?category=24">事项<br/>审批</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
