<?php

$this->title = 'Редагувати користувача '.$model->username;
$this->params['breadcrumbs'][] = ['label' => 'Користувачі', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('form', [
    'model' => $model,
    'courses_status' =>'update',
    'operation' => $operation,
]) ?>
