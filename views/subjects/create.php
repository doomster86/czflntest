<?php

use yii\helpers\Html;


$this->title = 'Створити новий предмет';
$this->params['breadcrumbs'][] = ['label' => 'Предмети', 'url' => ['all-subjects']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="subjects-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
        'subject_status' =>'create',
        'operation' => $operation,
    ]) ?>

</div>
