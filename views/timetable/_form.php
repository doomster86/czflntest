<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use bootui\datepicker\Datepicker;

/* @var $this yii\web\View */
/* @var $model app\models\Timetable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="timetable-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=  $form->field($model, 'datestart')
        ->label('Дата початку')
        ->widget(DatePicker::className(),[
        ]); ?>

    <?=  $form->field($model, 'dateend')
        ->label('Дата кінця')
        ->widget(DatePicker::className(),[
        ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
