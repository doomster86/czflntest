<?php

use yii\helpers\Html;

$this->title = 'Створити нову професію';
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="subjects-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
        'status' => $status,
        'current_action' => 'create',
        //'operation' => $operation,
    ]) ?>

</div>
