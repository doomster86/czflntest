<?php

namespace app\assets;

use yii\web\AssetBundle;

class Datepicker1 extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'js/datepicker/jquery-ui.css',
    ];

    public $js = [
        'revealator/jquery-1.11.3.min.js',
        'js/datepicker/datepicker.js',
    ];

    public $depends = [];
}