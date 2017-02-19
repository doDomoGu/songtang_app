<?php
use oa\models\TaskCategory;
use yii\helpers\Html;
$this->title = '';

$categoryType = TaskCategory::getTypeList();
oa\assets\AppAsset::addCssFile($this,'css/main/site/index.css');
?>


<?php $j=0;foreach($categoryType as $k=>$v):?>
    <div class="task-category <?=$j==0?'task-first':''?>" >
        <div class="category-heading">
            <h3 class="category-title"><?=$v?></h3>
        </div>
        <div class="category-list">
            <?php $list = TaskCategory::find()->where(['type'=>$k,'show_flag'=>1,'status'=>1])->orderBy('ord asc')->all();?>
            <?php if(!empty($list)):?>
                <?php $i=0;foreach($list as $l):?>
                <li <?=$i%3==2?'class="li-3rd"':''?> ><?=$l->name?></li>
                <?php $i++;endforeach;?>
            <?php endif;?>
            <?php if(count($list)%3>0):?>
            <?php for($k=count($list)%3;$k<3;$k++):?>
                <li <?=$k==2?'class="li-3rd"':''?> >&nbsp;</li>
            <?php endfor;?>
            <?php endif;?>
        </div>
    </div>

<?php $j++;endforeach;?>
