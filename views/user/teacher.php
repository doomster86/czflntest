<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TeacherMeta;

$type = TeacherMeta::TEACHER_TYPE;

if( Html::encode($operation) == 'teacher_updated'):
    ?>

    <div class="alert alert-success">
        <p>Оновлено!</p>
    </div>

    <?php
endif;
?>

<div class="teacher-meta-form">

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'data' => ['pjax' => true],
            'id' => 'teacher-form'
        ],
    ]);
    ?>

    <?php echo $form->field($model, 'teacher_type')->label('Тип викладача')->dropDownList($type);  ?>

    <?php echo $form->field($model, 'rank_id')->label('Педагогічне звання')->dropDownList($model->getAllRanks());  ?>

    <?php echo $form->field($model, 'degree_id')->label('Вчена ступінь')->dropDownList($model->getAllDegrees());  ?>

    <?php echo $form->field($model, 'skill_id')->label('Кваліфікація')->dropDownList($model->getAllSkills());  ?>

    <?= $form->field($model, 'hours')->label('Навантаження г./тиж.')->textInput() ?>

    <div id="workdays">
        <h3>Робочі дні</h3>

        <?= $form->field($model, 'monday')->checkbox(['label' => 'Понеділок']); ?>

        <?= $form->field($model, 'tuesday')->checkbox(['label' => 'Вівторок']); ?>

        <?= $form->field($model, 'wednesday')->checkbox(['label' => 'Середа']); ?>

        <?= $form->field($model, 'thursday')->checkbox(['label' => 'Четвер']); ?>

        <?= $form->field($model, 'friday')->checkbox(['label' => 'П\'ятниця']); ?>

        <?= $form->field($model, 'saturday')->checkbox(['label' => 'Суббота']); ?>

        <?= $form->field($model, 'sunday')->checkbox(['label' => 'Неділля']); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Додати' : 'Оновити', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'teacherbtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
