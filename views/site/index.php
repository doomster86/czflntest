<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Головна';

?>
    <?php if (Yii::$app->user->isGuest) { ?>
    <div class="site-login">
        <h1>Вхід до роскладу</h1>

        <p>Для того, щоб увійти, необхідно вказати ваші логін пароль:</p>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Логін') ?>

        <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} Запам'ятати мене</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

        <div class="form-group col-md-4">
            <div class="col-md-4">
                <?= Html::submitButton('Увійти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <div class="col-md-5" style="color:#999;margin:7px 0">
                <?= Html::a('Забули пароль?', ['site/request-password-reset']) ?>
            </div>
            <div class="col-md-5" style="color:#999;margin:7px 0">
                <?= Html::a('Зареєеструватись', ['site/signup']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
        <?php } else { ?>
            <div class="site-index">
                <h2>Ви увійшли як <?php
                    echo Yii::$app->user->identity->username; ?></h2>
                <?= Html::beginForm(['/site/logout'], 'post'); ?>
                <?= Html::submitButton(
                'Вийти',
                ['class' => 'btn btn-default']
                ); ?>
                <?= Html::a('Змінити пароль', ['user/pass'], ['class'=>'btn btn-default']) ?>
                <?= Html::endForm(); ?>
            </div>

        <?php } ?>
    </div>
