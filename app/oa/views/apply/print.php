<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use oa\models\Apply;

$this->title = '打印';
oa\assets\AppAsset::addJsFile($this,'js/main/apply/print.js');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/my.css');
oa\assets\AppAsset::addCssFile($this,'css/main/apply/print.css');

?>
<?=$html?>