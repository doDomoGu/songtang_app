<?php
    yun\assets\AppAsset::addCssFile($this,'css/main/version/index.css');

    $arr = [
        '0.15.1',
        '0.15.0',
        '0.14.0',
        '0.13.0',
        '0.12.0',
        '0.11.0',
        '0.10.0',
        '0.9.1',
        '0.9.0',
        '0.8.0',
        '0.7.0',
        '0.6.1',
        '0.6.0',
        '0.5.0',
        '0.4.0',
        '0.3.0',
        '0.2.0',
        '0.1.0',
    ];

    $reverse = false;
    if(Yii::$app->request->get('order',null)=='reverse'){
        $reverse = true;
        $arr = array_reverse($arr);
    }


?>
<p>
<?php if($reverse==true):?>
    <a href="/version" class="btn btn-success" >倒序查看 >></a>
<?php else: ?>
    <a href="/version?order=reverse" class="btn btn-success">正序查看 >></a>
<?php endif;?>
</p>
<section class="version-item">
    <?php foreach($arr as $a):?>
        <?=$this->render($a.'.php')?>
    <?php endforeach;?>
</section>
