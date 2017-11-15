<?php

$this->title = 'Редагувати професію';
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('form', [
    'model' => $model,
    'courses_status' =>'update',
    'operation' => $operation,
]) ?>
