<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

if( Html::encode($operation) == 'updated'):
    ?>

    <div class="alert alert-success">
        <p>Оновлено!</p>
    </div>

    <?php
endif;
?>

<div class="user-form">

    <?php
        $form = ActiveForm::begin([
            'options' => [
                'data' => ['pjax' => true],
                'id' => 'user-form'
            ],
        ]);
    ?>

    <h2><?= $model->username ?></h2>

    <?php
    echo $form->field($model, 'lastname')->label("Прізвище")->textInput([
        'placeholder' => "Введіть прізвище",
        'value' =>$model->lastname ])
    ?>

    <?php
    echo $form->field($model, 'firstname')->label("Ім'я")->textInput([
        'placeholder' => "Введіть ім'я",
        'value' =>$model->firstname ])
    ?>

    <?php
    echo $form->field($model, 'middlename')->label("По батькові")->textInput([
        'placeholder' => "Введіть по батькові",
        'value' =>$model->middlename ])
    ?>

    <?php
    echo $form->field($model, 'email')->label("Email")->textInput([
        'placeholder' => "Введіть по email",
        'value' =>$model->email ])
    ?>

    <?php
    echo $form->field($model, 'phone')->label("Телефон")->textInput([
        'placeholder' => "Введіть телефон",
        'value' =>$model->phone ])
    ?>

    <?php echo $form->field($model, 'status')->label('Встановити статус')->dropDownList($status);  ?>

    <?php echo $form->field($model, 'role')->label('Обрати роль')->dropDownList($roles);  ?>

    <div class="form-group">
        <?= Html::submitButton('Оновити', ['class' => 'btn btn-primary', 'name' => 'userbtn']) ?>
    </div>

    <?php
        ActiveForm::end();

    ?>

</div>
