<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LectureTable */

$this->title = 'Оновити пару: ' . $model->corpsName . ' (' . $model->time_start . ' - ' . $model->time_stop . ')';
$this->params['breadcrumbs'][] = ['label' => 'Пари', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Оновити';
?>
<div class="lecture-table-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
        'status_form' => $status,
        'dataProvider' => $dataProvider,
        'current_action' => 'update',
    ]) ?>

</div>
