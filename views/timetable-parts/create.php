<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TimetableParts */

$this->title = 'Створити розклад';
$this->params['breadcrumbs'][] = ['label' => 'Розклади', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timetable-parts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <p>Може зайняти деякий час, в залежності від тривалості періоду, на який формується розклад.</p>
    <p>Щоб створити розклад, вам необхідно:</p>
    <ul>
        <li>зареэструвати викладачів і заповнити поля з їх характеристиками</li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>
