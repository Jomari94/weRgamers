<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Fuentes de la aplicación.
 */
class FontAsset extends AssetBundle
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
        '//fonts.googleapis.com/css?family=Audiowide',
        '//fonts.googleapis.com/css?family=Open+Sans',
        '//fonts.googleapis.com/css?family=Roboto',
    ];

    /**
     * @var array Opciones del CSS
     */
    public $cssOptions = [
        'type' => 'text/css',
    ];
}
