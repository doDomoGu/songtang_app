<?php

namespace ucenter\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/site.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    //导入当前页的功能js文件，注意加载顺序，这个应该最后调用  文件路径相对@web即可
    public static function addJsFile($view, $jsfile) {
        $view->registerJsFile($jsfile, ['depends' => 'ucenter\assets\AppAsset']);
    }
    //导入当前页的功能js代码，注意加载顺序，这个应该最后调用  文件路径相对@web即可
    public static function addJs($view, $jsString) {
        $view->registerJs($jsString, ['depends' => 'ucenter\assets\AppAsset']);
    }
    //导入当前页的样式css文件，注意加载顺序，这个应该最后调用  文件路径相对@web即可
    public static function addCssFile($view, $cssfile) {
        $view->registerCssFile($cssfile,['depends' => 'ucenter\assets\AppAsset']);
    }
}
