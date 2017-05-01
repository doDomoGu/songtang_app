<?php
use oa\models\TaskCategory;
use yii\helpers\Html;
$this->title = '';

$categoryType = TaskCategory::getTypeList();
oa\assets\AppAsset::addCssFile($this,'css/main/site/index.css');
?>
<section id="task-index">

    <div class="task-content-2">
        <?php $i=1;foreach($categoryType as $k=>$v):?>
            <div class="task-list">
                <span class="task-title task-title-<?=$i?>">
                    <img src="/images/main/site/index_tag_<?=$i?>.png" />
                </span>
                <ul class="task-category-list ul-<?=$i?>">
                    <?php $list = TaskCategory::find()->where(['type'=>$k,'show_flag'=>1,'status'=>1])->orderBy('ord asc')->all();?>
                    <?php if(!empty($list)):?>
                        <?php foreach($list as $l):?>
                            <li><?=$l->name?></li>
                        <?php endforeach;?>
                    <?php endif;?>
                </ul>
            </div>

        <?php $i++;endforeach;?>
    </div>
</section>
