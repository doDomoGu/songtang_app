<?php
use oa\models\TaskCategory;
use yii\helpers\Html;
$this->title = '';

$categoryType = TaskCategory::getTypeList();

?>


<?php foreach($categoryType as $k=>$v):?>
    <div class="task-category panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?=$v?></h3>
        </div>
        <div class="panel-body">
            <?php $list = TaskCategory::find()->where(['type'=>$k,'show_flag'=>1,'status'=>1])->orderBy('ord asc')->all();?>
            <?php if(!empty($list)):?>
            <ul class="list-unstyled">
                <?php foreach($list as $l):?>
                <li><?=$l->name?></li>
                <?php endforeach;?>
            </ul>
            <?php endif;?>
        </div>
    </div>

<?php endforeach;?>
