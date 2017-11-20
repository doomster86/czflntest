<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Audience */

$this->title = 'Оновити: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Аудиторії', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Оновити';
?>

<div class="audience-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <hr />
    <?= $this->render('form', [
        'model' => $model,
        'corps' => $corps,
        'current_action' => 'update',
        'status_form' => $status
    ]) ?>

</div>
