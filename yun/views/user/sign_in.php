<?php
    use yii\helpers\BaseHtml;
    app\assets\AppAsset::addCssFile($this,'css/main/user/sign_in.css');
?>
<section>
    <?php if($result == 1):?>
        <p class="bg-success">
            签到成功! <?=BaseHtml::a('返回>>',['/user/sign'])?>
        </p>
    <?php elseif($result == 2):?>
        <p class="bg-warning">
            今日已签到! <?=BaseHtml::a('返回>>',['/user/sign'])?>
        </p>
    <?php elseif($result == 3):?>
        <p class="bg-warning">
            今天是节假日! <?=BaseHtml::a('返回>>',['/user/sign'])?>
        </p>
    <?php else:?>
        <p class="bg-danger">
            签到失败! <?=BaseHtml::a('返回>>',['/user/sign'])?>
        </p>
    <?php endif;?>
</section>