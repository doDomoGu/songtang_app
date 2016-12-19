<section id="sidebar" class="well well-sm">
    <ul class="list-unstyled">
        <li>
            姓名：<?=Yii::$app->user->getIdentity()->name?>
        </li>
        <li>
            地区：<?=Yii::$app->user->getIdentity()->area?>
        </li>
        <li>
            业态：<?=Yii::$app->user->getIdentity()->business?>
        </li>
        <li>
            部门：<?=Yii::$app->user->getIdentity()->department?>
        </li>
        <li>
            职位：<?=Yii::$app->user->getIdentity()->position?>
        </li>
    </ul>
</section>