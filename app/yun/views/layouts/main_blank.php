<?php

use yun\assets\AppAsset;

AppAsset::register($this);  /* 注册appAsset */
?>
<?php $this->beginPage(); /* 页面开始标志位 */ ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<?php echo $this->render('head'); /* 引入头部 */ ?>
<body>
<?php $this->beginBody(); /* body开始标志位 */ ?>


<?= $content ?>
<?php $this->endBody(); /* body结束标志位 */ ?>
</body>
</html>
<?php $this->endPage(); /* 页面结束标志位 */ ?>
