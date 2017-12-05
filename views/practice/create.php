<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Audience */

$this->title = 'Створити нову виробничу практику';
$this->params['breadcrumbs'][] = ['label' => 'Практика', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="subjects-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <hr />
    <?= $this->render('form', [
        'model' => $model,
        //'teachers' => $teachers,
        'current_action' => 'create',
        'status_form' => $status,
    ]) ?>

</div>
