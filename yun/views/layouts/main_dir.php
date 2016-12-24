<?php $this->beginPage(); /* 页面开始标志位 */ ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<?php echo $this->render('head'); /* 引入头部 */ ?>
<body>
<?php $this->beginBody(); /* body开始标志位 */ ?>


<div class="wrap">
    <?php echo $this->render($this->context->navbarView); /* 引入导航栏 */?>
    <div id="dir-container" class="container" style="padding-top:85px;">
        <div id="dir-sidebar">
            <?=$this->render('/dir/_left',['dir_id'=>$this->params['dir_id']])?>
        </div>

        <?php if($this->params['dir_id']==5):?>
        <?=$this->render('/dir/_rank_download')?>
        <?php else:?>
        <div id="dir-sidebar-right">
        <?=$this->render('/dir/_sidebar_right')?>
        </div>
        <?php endif;?>
        <div id="dir-main" class="<?=$this->params['dir_id']==5?'dir-study':''?>">
            <?= $content ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<?/*=$this->render('footer')*/?>
<?php $this->endBody(); /* body结束标志位 */ ?>
</body>
</html>
<?php $this->endPage(); /* 页面结束标志位 */ ?>
