<?php
use yii\bootstrap\Html;

?>

首页<br/><br/>

<?=Html::a('清除缓存','/site/clear-cache',['class'=>'btn btn-danger'])?>
<?php /*if(!Yii::$app->user->isGuest):*/?><!--


<?php /*else:*/?>
    <?/*=Html::a('登录',['site/login'],['class'=>'btn btn-success btn-lg col-lg-8 col-md-8 col-xs-8'])*/?>

--><?php /*endif;*/?>
