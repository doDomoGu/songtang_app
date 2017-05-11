<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

use login\assets\AppAsset;

AppAsset::register($this);  /* 注册appAsset */
AppAsset::addCssFile($this,'css/login.css');
AppAsset::addJsFile($this,'js/login.js');
?>

<?php
    $this->title = '颂唐机构 - 账号登录';
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


    <!--<p class="line-1 text-center">一站式地产综合服务商 http://www.songtang.net</p>
    <p class="line-2 text-center">China's Glory United</p>-->
    <section id="login-form-section">
        <article class="text-center">
            <img src="/images/site-login/word2.png" style="width:500px;"/>


            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => [/*'class' => 'form-horizontal',*/'autocomplete'=>'off'],
                //'enableAjaxValidation'=>false,
                //'enableClientValidation'=>false,
                'fieldConfig' => [
                    //'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-4\">{error}</div>",
                    'template' => "{input}{error}",
                    'labelOptions' => ['class' => 'col-lg-2 col-lg-offset-3 control-label'],
                ],
            ]); ?>
            <section id="form-input-group">
                <input style="display:none" />
                <?= $form->field($model, 'username',[
                    'inputTemplate' => '{input}',
                    'inputOptions'=>['placeholder' => 'Username | 用户名','autocomplete'=>'off']
                ])->textInput() ?>
                <input style="display:none">
                <input style="display:none">
                <input style="display:none">
                <input style="display:none">
                <?= $form->field($model, 'password',[
                    'inputTemplate' => '{input}',
                    'inputOptions'=>['placeholder' => 'Password | 密码','autocomplete'=>'off']
                ])->passwordInput() ?>
                <input type="submit" class="submit-btn" value="登录" />
                <?/*=$model->getFirstError('username')*/?>
            </section>
            <?/*= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-5 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) */?>

            <!--<div class="form-group hidden">
            <div class="col-lg-offset-5 col-lg-7">
                <?/*= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) */?>
            </div>
        </div>-->


            <?php ActiveForm::end(); ?>
        </article>
    </section>
    <footer class="text-center">
        Since 1993
    </footer>
</section>
<?php $this->endBody(); /* body结束标志位 */ ?>
</body>
</html>
<?php $this->endPage(); /* 页面结束标志位 */ ?>




