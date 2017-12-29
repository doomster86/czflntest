<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TimetableParts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="timetable-parts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    //$datetime = strtotime('now');
    //echo $datetime;
    //echo Yii::$app->dtConverter->toDisplayDate($datetime);
    ?>

    <?=  $form->field($model, 'datestart')
        ->widget(DatePicker::className(),[
        ]); ?>

    <?=  $form->field($model, 'dateend')
        ->widget(DatePicker::className(),[
        ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Створити' : 'Оновити', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
