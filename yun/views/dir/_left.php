<?php
    use yun\assets\AppAsset;
    use yun\models\Dir;
    use common\components\CommonFunc;

    AppAsset::addCssFile($this,'css/ztree/zTreeStyle/zTreeStyle.css');
    AppAsset::addJsFile($this,'js/jquery.ztree.core-3.5.min.js');

AppAsset::addCssFile($this,'css/main/dir/_left.css');
AppAsset::addJsFile($this,'js/main/dir-_left.js');
//$start = microtime(true);
$treeData = CommonFunc::getByCache(Dir::className(),'getTreeData',[$dir_id],'yun:dir/tree-data');

/*$end = microtime(true);
$s = $end-$start;
Yii::info($flag.' | time : '.$s,'youhua');*/
?>
<span id="treeData" class="hidden"><?=$treeData?></span>
<div id="tree-div">
    <h3>快速访问<?/*=var_dump($cache['aa'])*/?></h3>
    <ul id="treeDemo" class="ztree"></ul>
</div>