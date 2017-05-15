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
                <div class="task-category-list ul-<?=$i?>">
                    <?php $list = TaskCategory::find()->where(['type'=>$k,'show_flag'=>1,'status'=>1])->orderBy('ord asc')->all();?>
                    <?php if(!empty($list)):?>
                        <?php if($k==1):?>
                            <?php $list1 = array_slice($list,0,6);$list2 = array_slice($list,6,6);?>
                            <ul>
                            <?php foreach($list1 as $l):?>
                                <li><a href="/apply/create?category=<?=$l->id?>"><?=$l->name?></a></li>
                            <?php endforeach;?>
                            </ul>
                            <ul>
                            <?php foreach($list2 as $l):?>
                                <li><a href="/apply/create?category=<?=$l->id?>"><?=$l->name?></a></li>
                            <?php endforeach;?>
                            </ul>
                        <?php else:?>
                        <?php foreach($list as $l):?>
                                <li><a href="/apply/create?category=<?=$l->id?>"><?=$l->name?></a></li>
                        <?php endforeach;?>
                        <?php endif;?>
                    <?php endif;?>
                </div>
            </div>

        <?php $i++;endforeach;?>
    </div>
</section>
