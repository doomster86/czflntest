<?php

use app\models\User;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;


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

<?php Pjax::begin([ ]); ?>

    <div class="col-lg-12">
        <h2><?= $model->username ?></h2>
    </div>

    <div class="col-lg-6">
        <?= $this->render('form', [
            'model' => $model,
            'courses_status' =>'update',
            'operation' => $operation,
            'status' => $arrStatus,
            'roles' => $arrRoles,
        ]) ?>
    </div>

    <div class="col-lg-6">
        <?php
        if( $model->role==User::ROLES['0']['roles'] ) {
            echo $this->render('student', [
                'model' => $student,
                'courses_status' =>'update',
                'operation' => $operation,
                'status' => $arrStatus,
                'roles' => $arrRoles,
            ]);
        }

        if( $model->role==User::ROLES['1']['roles'] ) {
            echo $this->render('admin', [
                'model' => $model,
                'courses_status' =>'update',
                'operation' => $operation,
                'status' => $arrStatus,
                'roles' => $arrRoles,
            ]);
        }

        if( $model->role==User::ROLES['2']['roles'] ) {
            echo $this->render('teacher', [
                'model' =>$teacher,
                'courses_status' =>'update',
                'operation' => $operation,
                'status' => $arrStatus,
                'roles' => $arrRoles,
            ]);
        }
        ?>
    </div>

    <div class="col-lg-12">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($password, 'password')->passwordInput()->label('Новий пароль') ?>
        <?= $form->field($password, 'confirm_password')->passwordInput()->label('Повторіть новий пароль') ?>

        <div class="form-group">
            <?= Html::submitButton('Змінити', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

<?php Pjax::end(); ?>