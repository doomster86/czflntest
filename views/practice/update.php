<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Audience */

$this->title = 'Оновити: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Практика', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Оновити';

?>
<div class="subjects-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <hr />

    <?= $this->render('form', [
        'model' => $model,
        //'teachers' => $teachers,
        'current_action' => 'update',
        'status_form' => $status
        /*
        'subject_status' =>'update',
        'operation' => $operation,
        */
    ]) ?>
</div>