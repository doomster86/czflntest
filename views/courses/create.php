<?php

use yii\helpers\Html;

$this->title = 'Створити нову професію';
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['courses']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="subjects-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
        'courses_status' =>'create',
        'operation' => $operation,
        'subjects' => $subjects,
    ]) ?>

</div>
