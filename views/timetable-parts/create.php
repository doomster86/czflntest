<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TimetableParts */

$this->title = 'Створити розклад';
$this->params['breadcrumbs'][] = ['label' => 'Розклади', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timetable-parts-create">

    <h1><?= Html::encode($this->title) ?>*</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <p>
        *тут ви можете створити сам період для формування розкладу, таблицю. Далі потрібно відкрити цей період і створити
        розклад для кожної групи окремо.
    </p>

    <p>Може зайняти деякий час, в залежності від тривалості періоду, на який формується розклад.</p>

</div>
