<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model app\models\Groups */

$this->title = 'Редагувати групу: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Всі групи', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Оновити';
?>

<div class="groups-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <hr />
    <?= $this->render('form', [
        'model' => $model,
        'courses' => $courses,
	    'curators' => $curators,
	    'current_action' => 'update',
        'status' => $status
    ]) ?>

</div>
