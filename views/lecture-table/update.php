<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LectureTable */

$this->title = 'Оновити пару: ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Пари', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Оновити';
?>
<div class="lecture-table-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
    ]) ?>

</div>
