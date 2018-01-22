<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

if( Html::encode($status) == 'added'):
	?>

    <div class="alert alert-success">
        <p>Нового користувача додано!</p>
    </div>

	<?php
	$model->username = '';
	$model->email = '';
endif;

?>

<div class="create-user-form">
	<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
	<?php echo $form->field($model, 'username')->textInput(['autofocus' => true])->label('Логін'); ?>
	<?php echo $form->field($model, 'email')->label('Email'); ?>
	<?php echo $form->field($model, 'password')->label('Пароль')->passwordInput([
	        'placeholder' => 'Введіть пароль',
	        'value' => ''
		]); ?>
    <div class="form-group">
		<?= Html::submitButton('Зареєструвати', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
    </div>
	<?php ActiveForm::end(); ?>
</div>
