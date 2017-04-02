<?php
    use yii\widgets\Breadcrumbs;
    use common\components\CommonFunc;
    yun\assets\AppAsset::addCssFile($this,'css/main/dir/index.css');
?>
<div id="list-head">
    <div id="dir-nav">
<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>
    </div>
</div>

<div id="list-main">
<?php foreach($list as $l):?>
    <?php if($l->link!=''):?>
        <div class="dir-item text-center link-item">
            <a class="alink" href="<?=$l->link?>" target="_blank" title="<?=$l->name?>" alt="<?=$l->name?>">
                <div class="icon" style="background: #feecb6">
                    <img src="/images/fileicon/documents.png">
                </div>
                <div class="name" ><?=CommonFunc::mySubstr($l->name,18)?></div>
            </a>
        </div>
    <?php else:?>
        <div class="dir-item text-center">
            <a class="alink" href="/dir?dir_id=<?=$l->id?>" title="<?=$l->name?>" alt="<?=$l->name?>">
                <div class="icon">
                    <img src="/images/fileicon/documents.png">
                </div>
                <div class="name" ><?=CommonFunc::mySubstr($l->name,18)?></div>
            </a>
        </div>
    <?php endif;?>
<?php endforeach;?>
</div>
<div class="clearfix"></div>