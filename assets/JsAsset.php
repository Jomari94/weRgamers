<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Assets de Javascript
 */
class JsAsset extends AssetBundle
{
    /**
     * @var string Directorio base
     */
    public $basePath = '@webroot';

    /**
     * @var string Url de en el que se encuentran los assets
     */
    public $baseUrl = '@web';

    /**
     * @var array Archivos CSS de la aplicación
     */
    public $css = [
    ];

    /**
     * @var array Archivos Javascript de la aplicación
     */
    public $js = [
        'js/jquery.countdown.js',
        'js/moment.js',
        'js/moment-timezone-with-data.js',
        'js/jquery.knob.js',
        'js/typeahead.jquery.js',
        'js/bloodhound.js',
        'js/search.js',
        'js/socket.io.js',
    ];

    /**
     * @var array Dependencias del asset
     */
    public $depends = [
        '\yii\web\JqueryAsset',
    ];
}
