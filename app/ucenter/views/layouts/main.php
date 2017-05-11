<?php


use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use ucenter\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?=Yii::$app->id.($this->title!=''?'_'.Html::encode($this->title):'') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<section>
    <?=$this->render('side_nav')?>
    <?=$this->render('alert')?>
    <div class="main-content" ><!--
        <?/*=$this->render('_header')*/?>
        -->
        <?=$this->render('page_head')?>
        <div class="wrapper">
            <?=$content?>
        </div><!--
        <?/*=$this->render('_footer')*/?>
    --></div>
</section>

<!--<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; songtang.net <?/*= date('Y') */?></p>

        <p class="pull-right"><a href="http://xinmengweb.com" target="_blank">Xinmeng Web</a> </p>
    </div>
</footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
