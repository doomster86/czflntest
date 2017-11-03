<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->title = 'Доступ заборонено';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Ви не маєте доступу до цієї сторінки.
    </p>
    <code><?= __FILE__ ?></code>
</div>
