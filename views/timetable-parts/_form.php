<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TimetableParts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="timetable-parts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'datestart')->textInput() ?>

    <?= $form->field($model, 'dateend')->textInput() ?>

    <?= $form->field($model, 'cols')->textInput() ?>

    <?= $form->field($model, 'rows')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
