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

    <p>Бажано створювати розклад на місяць (з першого до останнього дня включно).<br/>Може зайняти 5-10 хвилин.</p>
</div>
