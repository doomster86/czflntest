<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Corps */

$this->title = 'Оновити: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Corps', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Оновити';
?>
<div class="corps-update">

    <h1><?= Html::encode($this->title) ?></h1>


    <hr />

    <?= $this->render('_form', [
        'model' => $model,
        'current_action' => 'update',
        'status_form' => $status
    ]) ?>

</div>
