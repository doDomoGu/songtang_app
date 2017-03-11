<?php
use oa\models\TaskCategory;
use yii\helpers\Html;
$this->title = '';

$categoryType = TaskCategory::getTypeList();
oa\assets\AppAsset::addCssFile($this,'css/main/site/index.css');
?>
<section id="task-index">
    <div class="task-content">
        <?php $i=1;foreach($categoryType as $k=>$v):?>
            <ul class="ul-<?=$i?>">
            <?php $list = TaskCategory::find()->where(['type'=>$k,'show_flag'=>1,'status'=>1])->orderBy('ord asc')->all();?>
            <?php if(!empty($list)):?>
                <?php foreach($list as $l):?>
                <li><?=$l->name?></li>
                <?php endforeach;?>
            <?php endif;?>
            </ul>
        <?php $i++;endforeach;?>
    </div>
</section>
