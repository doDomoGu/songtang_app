<?php
use yii\bootstrap\Html;
    yun\assets\AppAsset::addCssFile($this,'css/main/site/_recruitment.css');
    $list = yun\models\Recruitment::find()->where(['status'=>1])->orderBy('ord desc,edit_time desc')->limit(8)->all();
?>
<section id="recruitment-section" >
    <div class="recruit-heading">
        <span class="sp1">Recruitment</span>
        <span class="sp2">招聘信息</span>
    </div>
    <div class="recruit-list">
        <ul class="list-unstyled">
            <?php foreach($list as $l):?>
            <li>
                <?php if($l->link_url!=''):?>
                    <?=Html::a($l->title,$l->link_url,['target'=>'_blank'])?>
                <?php else:?>
                    <?=$l->title?>
                <?php endif;?>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</section>