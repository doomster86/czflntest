<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model app\models\Groups */

$this->title = 'Редагувати групу: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Всі групи', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Оновити';
$formatter = new \yii\i18n\Formatter;
if ($model->date_start) {
    $model->date_start = $formatter->asDate($model->date_start, 'dd.MM.yyyy');
} else {
    $model->date_start = '';
}
if ($model->date_end) {
    $model->date_end = $formatter->asDate($model->date_end, 'dd.MM.yyyy');
} else {
    $model->date_end = '';
}

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
