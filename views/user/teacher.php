<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TeacherMeta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacher-meta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'teacher_type')->textInput() ?>

    <?= $form->field($model, 'rank_id')->textInput() ?>

    <?= $form->field($model, 'degree_id')->textInput() ?>

    <?= $form->field($model, 'skill_id')->textInput() ?>

    <?= $form->field($model, 'hours')->textInput() ?>

    <?= $form->field($model, 'monday')->textInput() ?>

    <?= $form->field($model, 'tuesday')->textInput() ?>

    <?= $form->field($model, 'wednesday')->textInput() ?>

    <?= $form->field($model, 'thursday')->textInput() ?>

    <?= $form->field($model, 'friday')->textInput() ?>

    <?= $form->field($model, 'saturday')->textInput() ?>

    <?= $form->field($model, 'sunday')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Додати' : 'Оновити', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'teacherbtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
