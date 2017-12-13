<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TimetableParts */

$this->title = 'Create Timetable Parts';
$this->params['breadcrumbs'][] = ['label' => 'Timetable Parts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timetable-parts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
