<?php

namespace oa\modules\admin\assets;

use yii\web\AssetBundle;
use Yii;


class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot/adminAssets';
    public $baseUrl = '@web/adminAssets';
    public $css = [
        'css/site.css'

    ];
    public $js = [
        'js/site.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->css[] = Yii::$app->params['ucenterAppUrl'].'/css/app_entry.css';
    }

    //导入当前页的功能js文件，注意加载顺序，这个应该最后调用  文件路径相对@web即可
    public static function addJsFile($view, $jsfile) {
        $view->registerJsFile('@web/adminAssets/'.$jsfile, ['depends' => 'oa\modules\admin\assets\AdminAsset']);
    }

    //导入当前页的功能js代码，注意加载顺序，这个应该最后调用  文件路径相对@web即可
    public static function addJs($view, $jsString) {
        $view->registerJs($jsString, ['depends' => 'oa\modules\admin\assets\AdminAsset']);
    }

    //导入当前页的样式css文件，注意加载顺序，这个应该最后调用  文件路径相对@web即可
    public static function addCssFile($view, $cssfile) {
        $view->registerCssFile('@web/adminAssets/'.$cssfile, ['depends' => 'oa\modules\admin\assets\AdminAsset']);
    }
}
