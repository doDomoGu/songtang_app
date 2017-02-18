<?php
    use yii\helpers\Html;
    use login\assets\AppAsset;
    use yii\bootstrap\ActiveForm;

    AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?=Yii::$app->name. ($this->title?'_'.Html::encode($this->title):'') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap" id="login">
    <section class="head-logo">
        <img src="/images/logo.png" />
    </section>

    <section class="main">
        <div class="middle-image">
            <img src="/images/site-login/middle.png" />
        </div>
        <div class="motto">
            <span class="motto-1">一站式地产专业服务商</span>
            <span class="motto-2">www.songtang.net</span>
        </div>

        <div class="login-form">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{input}{error}"
                ]
            ]); ?>

            <?= $form->field($model, 'username',[
                'inputTemplate' => '{input}',
                'inputOptions'=>['placeholder' => 'Username | 用户名','autocomplete'=>'off']
            ])->textInput() ?>

            <?= $form->field($model, 'password',[
                'inputTemplate' => '{input}',
                'inputOptions'=>['placeholder' => 'Password | 密码','autocomplete'=>'off','class' => 'form-control password-input'],

            ])->passwordInput() ?>

            <input type="submit" class="submit-btn" value="登录" />

           <!-- <?/*= $form->field($model, 'username')->textInput(['autofocus' => true]) */?>

            <?/*= $form->field($model, 'password')->passwordInput() */?>

            <?/*= $form->field($model, 'rememberMe')->checkbox() */?>

            <div class="form-group">
                <?/*= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) */?>
            </div>-->

            <?php ActiveForm::end(); ?>
        </div>
    </section>
</div>
<footer class="footer">
    <!--<div class="container">
        <p class="pull-left">&copy; My Company <?/*= date('Y') */?></p>

        <p class="pull-right"><?/*= Yii::powered() */?></p>
    </div>-->
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>



