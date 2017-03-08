<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

use login\assets\AppAsset;

AppAsset::register($this);  /* 注册appAsset */
AppAsset::addCssFile($this,'css/login_success.css');
AppAsset::addJsFile($this,'js/login_success.js');
?>

<?php
    //$hasFrontend = false;
    $hasAdmin = false;
    //$isYunFrontend = false;
    //$isOaFrontend = false;
    $isUcenterAdmin = false;
    $isOaAdmin = false;
    $isYunAdmin = false;

    $user = Yii::$app->user->identity;

    /*if($user->isYunFrontend){
        $isYunFrontend = true;
        $hasFrontend = true;
    }

    if($user->isOaFrontend){
        $isOaFrontend = true;
        $hasFrontend = true;
    }*/

    if($user->isUcenterAdmin){
        $isUcenterAdmin = true;
        $hasAdmin = true;
    }
    if($user->isOaAdmin){
     $isOaAdmin = true;
     $hasAdmin = true;
    }
    if($user->isYunAdmin){
      $isYunAdmin = true;
      $hasAdmin = true;
    }
?>
<?php $this->beginPage(); /* 页面开始标志位 */ ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); /* body开始标志位 */ ?>
<section id="site-login">
    <header class="text-center">
        <img class="logo" src="/images/logo.png" />
    </header>


    <div class="text-center main-content">
        <div class="success-div">
            <img src="/images/site-login-success/success.png"/>
        </div>
        <div class="word-div">
            <img src="/images/site-login/word2.png" style="width:500px;"/>
        </div>
        <div class="goto">
            <div class="title">
                您可以前往
            </div>
            <ul>
                <li>
                    <?=Html::a('颂唐OA',Yii::$app->params['oaAppUrl'],['class'=>'btn btn-default btn-xs'])?>
                </li>
                <li>
                    <?=Html::a('颂唐云',Yii::$app->params['yunAppUrl'],['class'=>'btn btn-default btn-xs'])?>
                </li>
            </ul>
        </div>
        <?php /*if($hasFrontend):*/?><!--
        <div class="goto">
            <div class="title">
                您可以前往
            </div>
            <ul>
                <?php /*if($isOaFrontend):*/?>
                <li>
                    <?/*=Html::a('颂唐OA',Yii::$app->params['oaAppUrl'],['class'=>'btn btn-default btn-xs'])*/?>
                </li>
                <?php /*endif;*/?>
                <?php /*if($isYunFrontend):*/?>
                <li>
                    <?/*=Html::a('颂唐云',Yii::$app->params['yunAppUrl'],['class'=>'btn btn-default btn-xs'])*/?>
                </li>
                <?php /*endif;*/?>
            </ul>
        </div>
        --><?php /*endif;*/?>
        <?php if($hasAdmin):?>
        <div class="goto">
           <div class="title">
               如果您是管理员 <br/>可以前往
           </div>
           <ul>
                <?php if($isUcenterAdmin):?>
                <li>
                   <?=Html::a('颂唐用户中心',Yii::$app->params['ucenterAppUrl'],['class'=>'btn btn-default btn-xs'])?>
                </li>
                <?php endif;?>
                <?php if($isOaAdmin):?>
                <li>
                   <?=Html::a('颂唐OA后台',Yii::$app->params['oaAppAdminUrl'],['class'=>'btn btn-default btn-xs'])?>
                </li>
                <?php endif;?>
                <?php if($isYunAdmin):?>
                <li>
                   <?=Html::a('颂唐云后台',Yii::$app->params['yunAppAdminUrl'],['class'=>'btn btn-default btn-xs'])?>
                </li>
                <?php endif;?>
           </ul>
        </div>
        <?php endif;?>
    </div>

    <footer class="text-center">
        Since 1993
    </footer>
</section>
<?php $this->endBody(); /* body结束标志位 */ ?>
</body>
</html>
<?php $this->endPage(); /* 页面结束标志位 */ ?>






