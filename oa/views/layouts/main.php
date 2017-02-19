<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use oa\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?=Yii::$app->id. ($this->title?'_'.Html::encode($this->title):'') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php
NavBar::begin([
    'brandLabel' => Html::img('/images/logo.png'),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-default navbar-fixed-top',
        'id'=>'top-navbar'
    ],
]);
    $menuItems = [];
    $menuItems[] = ['label' => '发起申请', 'url' => Url::to('/apply/create'),'options'=>['class'=>'nav-create-btn']];
    $menuItems[] = ['label' => '我的申请', 'url' => Url::to('/apply/my'),'options'=>['class'=>'nav-do-btn'],'active'=>$this->context->getRoute()=='apply/my'?true:false];
    $menuItems[] = ['label' => '待办事项', 'url' => Url::to('/apply/todo'),'options'=>['class'=>'nav-do-btn'],'active'=>$this->context->getRoute()=='apply/todo'||$this->context->getRoute()=='apply/do'?true:false];
    $menuItems[] = ['label' => '相关事项', 'url' => Url::to('/apply/related'),'options'=>['class'=>'nav-do-btn'],'active'=>$this->context->getRoute()=='apply/related'?true:false];
    $menuItems[] = ['label' => '办结事项', 'url' => Url::to('/apply/done'),'options'=>['class'=>'nav-do-btn'],'active'=>$this->context->getRoute()=='apply/done'?true:false];
    $menuItems[] = ['label' => '安全退出', 'url' => Yii::$app->params['logoutUrl'],'options'=>['class'=>'nav-exit-btn'],];
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right','id'=>'top-nav'],

    'items' => $menuItems,
]);
NavBar::end();
?>
<div class="wrap">
    <div class="container">
        <?=$this->render('sidebar')?>
        <?/*=$this->render('page_head')*/?>
        <section id="main">
            <?= $content ?>
        </section>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="text-center">&copy; 颂唐机构 <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
