<section id="sidebar" class="well well-sm">
    <div style="text-align: center;border-bottom: 1px solid #333;padding-bottom:4px;margin-bottom:10px;">
        职员信息
    </div>
    <ul class="list-unstyled">
        <li>
            <span class="col">姓名：</span>
            <span class="val"><?=Yii::$app->user->getIdentity()->name?></span>
        </li>
        <li>
            <span class="col">地区：</span>
            <span class="val"><?=Yii::$app->user->getIdentity()->area?></span>
        </li>
        <li>
            <span class="col">业态：</span>
            <span class="val"><?=Yii::$app->user->getIdentity()->business?></span>
        </li>
        <li>
            <span class="col">部门：</span>
            <span class="val"><?=Yii::$app->user->getIdentity()->department?></span>
        </li>
        <li>
            <span class="col">职位：</span>
            <span class="val"><?=Yii::$app->user->getIdentity()->position?></span>
        </li>
    </ul>
</section>