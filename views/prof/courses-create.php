<?php

$this->title = 'Створити нову професію';
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['courses']];
$this->params['breadcrumbs'][] = $this->title; ?>

<?= $this->render('form', [
    'model' => $model,
    'courses_status' =>'create',
    'operation' => $operation,
    'subjects' => $subjects,
]) ?>