<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Corps */

$this->title = 'Update Corps: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Corps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="corps-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
