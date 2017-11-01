<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Audience */

$this->title = 'Оновити аудиторію: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Аудиторії', 'url' => ['audience']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Оновити';
?>
<div class="audience-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
