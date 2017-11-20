<?php

use app\models\User;

$this->title = 'Редагувати користувача '.$model->username;
$this->params['breadcrumbs'][] = ['label' => 'Користувачі', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

foreach (User::STATUS as $statusName) {
    $arrStatus[] = $statusName['name'];
}

foreach (User::ROLES as $rolesName) {
    $arrRoles[] = $rolesName['name'];
}
?>

<?= $this->render('form', [
    'model' => $model,
    'courses_status' =>'update',
    'operation' => $operation,
    'status' => $arrStatus,
    'roles' => $arrRoles,
]) ?>
