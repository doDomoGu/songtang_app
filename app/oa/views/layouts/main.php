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
    $menuItems[] = ['label' => '首页', 'url' => Url::to('/'),'options'=>['class'=>'nav-do-btn'],'active'=>$this->context->getRoute()=='site/index'?true:false];
    $menuItems[] = ['label' => '发起申请', 'url' => Url::to('/apply/create'),'options'=>['class'=>'nav-do-btn'],'active'=>$this->context->getRoute()=='apply/create'?true:false];
    $menuItems[] = ['label' => '我的申请', 'url' => Url::to('/apply/my'),'options'=>['class'=>'nav-do-btn'],'active'=>$this->context->getRoute()=='apply/my'?true:false];
    $menuItems[] = ['label' => '待办事项', 'url' => Url::to('/apply/todo'),'options'=>['class'=>'nav-do-btn'],'active'=>$this->context->getRoute()=='apply/todo'||$this->context->getRoute()=='apply/do'?true:false];
    $menuItems[] = ['label' => '相关事项', 'url' => Url::to('/apply/related'),'options'=>['class'=>'nav-do-btn'],'active'=>$this->context->getRoute()=='apply/related'?true:false];
    $menuItems[] = ['label' => '办结事项', 'url' => Url::to('/apply/done'),'options'=>['class'=>'nav-do-btn'],'active'=>$this->context->getRoute()=='apply/done'?true:false];
    $menuItems[] = ['label' => '安全退出', 'url' => Yii::$app->params['logoutUrl'],'options'=>['class'=>'nav-exit-btn']];
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right','id'=>'top-nav'],

    'items' => $menuItems,
]);
NavBar::end();
?>
<?php if(Yii::$app->controller->route=='site/index'):?>
    <div style="background:url('/images/main/site/banner_bg.jpg');height:400px;overflow: hidden;margin-top:130px;text-align: center;">
        <img src="/images/main/site/banner.jpg" style="height:400px;width:1200px;margin:0 auto;" />
    </div>
<?php endif;?>
<div class="wrap">
    <div class="container" <?php if(Yii::$app->controller->route=='site/index'):?>style="padding-top:16px;"<?php endif;?>>
        <?=$this->render('sidebar')?>
        <?/*=$this->render('page_head')*/?>
        <section id="main">
            <?= $content ?>
        </section>
    </div>
</div>
<footer class="footer">
    <div style="text-align: center;">
        <div class="logo-line">
            <img src="<?=Yii::$app->params['yunAppUrl']?>/images/footer.png"  style="width:1140px;"/>
        </div>
        <div class="copyright">
            Copyright © 1993 - 2017 <strong><a style="text-decoration: none;color:#333;" href="http://www.songtang.net/">颂唐机构</a></strong> . All rights reserved
        </div>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
