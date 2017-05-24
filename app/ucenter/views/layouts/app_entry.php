<?php
use yii\bootstrap\Html;

$isYunFrontend = false;
$isOaFrontend = false;

$isUcenterAdmin = false;

$isYunBackendAdmin = false;
$isOaBackendAdmin = false;

$user = Yii::$app->user->identity;

if($user->isYunFrontend || $user->isYunFrontendAdmin || $user->isSuperAdmin){
    $isYunFrontend = true;
}

if($user->isOaFrontend  || $user->isOaFrontendAdmin || $user->isSuperAdmin){
    $isOaFrontend = true;
}

if($user->isUcenterAdmin || $user->isSuperAdmin){
    $isUcenterAdmin = true;
}

if($user->isYunBackendAdmin || $user->isSuperAdmin){
    $isYunBackendAdmin = true;
}

if($user->isOaBackendAdmin || $user->isSuperAdmin){
    $isOaBackendAdmin = true;
}
?>


<div id="app-entry">
    <div class="entry-title">其他应用入口</div>
    <ul>
        <?php if($isYunFrontend):?>
            <li><?=Html::a('颂唐云 >>',Yii::$app->params['yunAppUrl'],['target'=>'_blank'])?></li>
        <?php endif;?>
        <?php if($isOaFrontend):?>
            <li><?=Html::a('颂唐OA >>',Yii::$app->params['oaAppUrl'],['target'=>'_blank'])?></li>
        <?php endif;?>
        <?php if($isUcenterAdmin && $current!='ucenterAdmin'):?>
            <li><?=Html::a('职员管理系统 >>',Yii::$app->params['ucenterAppUrl'],['target'=>'_blank'])?></li>
        <?php endif;?>
        <?php if($isYunBackendAdmin && $current!='yunBackendAdmin'):?>
            <li><?=Html::a('颂唐云后台 >>',Yii::$app->params['yunAppAdminUrl'],['target'=>'_blank'])?></li>
        <?php endif;?>
        <?php if($isOaBackendAdmin && $current!='oaBackendAdmin'):?>
            <li><?=Html::a('颂唐OA后台 >>',Yii::$app->params['oaAppAdminUrl'],['target'=>'_blank'])?></li>
        <?php endif;?>
    </ul>
</div>