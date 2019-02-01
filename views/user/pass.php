<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ChangePasswordForm */
/* @var $form ActiveForm */

$this->title = 'Зміна пароля';
?>
<div class="user-changePassword">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'password')->passwordInput()->label('Новий пароль') ?>
    <?= $form->field($model, 'confirm_password')->passwordInput()->label('Повторіть новий пароль') ?>

    <div class="form-group">
        <?= Html::submitButton('Змінити', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>