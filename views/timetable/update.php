<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Timetable */

$this->title = 'Редагувати заняття: ' . $model->id;
?>
<div class="timetable-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
