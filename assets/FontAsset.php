<?php
namespace app\assets;

use yii\web\AssetBundle;

class FontAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//fonts.googleapis.com/css?family=Audiowide',
        '//fonts.googleapis.com/css?family=Open+Sans',
        '//fonts.googleapis.com/css?family=Roboto',
    ];
    public $cssOptions = [
        'type' => 'text/css',
    ];
}
