<?php

/* @var $this yii\web\View */

$this->title = '';
if(Yii::$app->user->isGuest){
    echo '0000';
}else{
    echo '1111';
}
?>

