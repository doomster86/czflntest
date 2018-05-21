<?php

namespace app\assets;

use yii\web\AssetBundle;


class Relevator extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'revealator/fm.revealator.jquery.css',
    ];

    public $js = [
        'revealator/jquery-1.11.3.min.js',
        'revealator/fm.revealator.jquery.js',
    ];

    public $depends = [];
}
