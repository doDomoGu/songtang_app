<?php

use yii\helpers\Html;
use yun\modules\admin\assets\AdminAsset;

AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?=Yii::$app->name.($this->title!=''?'_'.Html::encode($this->title):'') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<section>
    <?=$this->render('side_nav')?>
    <?=$this->render('alert')?>
    <div class="main-content" >
        <?=$this->render('page_head')?>
        <div class="wrapper">
            <?=$content?>
        </div>
    </div>
</section>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
