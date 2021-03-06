<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rank */

$this->title = 'Оновити звання: ';
$this->params['breadcrumbs'][] = ['label' => 'Звання', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'оновити';
?>
<div class="rank-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
        'status' => $status,
        'current_action' => 'update',
    ]) ?>

</div>
