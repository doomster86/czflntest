<?php

use yii\helpers\Html;

$this->title = 'Редагувати предмет';
$this->params['breadcrumbs'][] = ['label' => 'Предмети', 'url' => ['all-subjects']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', [
    'model' => $model,
    'subject_status' =>'update',
    'operation' => $operation,
]) ?>
