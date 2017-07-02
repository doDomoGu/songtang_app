<?php

echo '没有权限';

echo  \yii\bootstrap\Html::a('退出',Yii::$app->params['loginUrl'].'/site/logout');