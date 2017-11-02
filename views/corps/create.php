<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Corps */

$this->title = 'Додати корпус';
$this->params['breadcrumbs'][] = ['label' => 'Корпус', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="corps-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Статус: <?= Html::encode($status)?></p>

    <hr />
    <?= $this->render('_form', [
        'model' => $model,
        'current_action' => 'create',
        'status_form' => $status
    ]) ?>
</div>
