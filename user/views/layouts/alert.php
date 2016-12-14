<?php
    use yii\bootstrap\Alert;
?>
<section id="alert-section">
<?php if( Yii::$app->getSession()->hasFlash('success') ):?>
<div id="alert-success-div" class="top-alert" >
    <?=Alert::widget([
        'options' => [
            'class' => 'alert-success', //这里是提示框的class
            'id' => 'alert-success'
        ],
        'body' => Yii::$app->getSession()->getFlash('success'), //消息体
    ]);?>
</div>
<?php endif;?>

<?php if( Yii::$app->getSession()->hasFlash('error') ):?>
<div id="alert-error-div" class="top-alert" >
    <?=Alert::widget([
        'options' => [
            'class' => 'alert-danger',
            'id' => 'alert-error'
        ],
        'body' => Yii::$app->getSession()->getFlash('error'),
    ])?>
</div>
<?php endif;?>

<?php if( Yii::$app->getSession()->hasFlash('info') ):?>
<div id="alert-info-div" class="top-alert" >
    <?=Alert::widget([
        'options' => [
            'class' => 'alert-info',
            'id' => 'alert-info'
        ],
        'body' => Yii::$app->getSession()->getFlash('info'),
    ])?>
</div>
<?php endif;?>
</section>
