<?php
use yii\bootstrap\Html;
$this->title = '登录成功';

?>

<h3>登录成功！</h3>

<div>
    您可以前往:
    <ul>
        <li>
            <?=Html::a('颂唐OA',Yii::$app->params['oaAppUrl'])?>
        </li>
        <li>
            <?=Html::a('颂唐云',Yii::$app->params['yunAppUrl'])?>
        </li>
    </ul>
    如果您是管理员，还可以前往:
    <ul>
        <li>
            <?=Html::a('颂唐用户中心',Yii::$app->params['ucenterAppUrl'])?>
        </li>
        <li>
            <?=Html::a('颂唐OA后台',Yii::$app->params['oaAppAdminUrl'])?>
        </li>
        <li>
            <?=Html::a('颂唐云后台',Yii::$app->params['yunAppAdminUrl'])?>
        </li>
    </ul>
</div>