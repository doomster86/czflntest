<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

if( Html::encode($operation) == 'student_updated'):
    ?>

    <div class="alert alert-success">
        <p>Оновлено!</p>
    </div>

    <?php
endif;
?>

<div class="student-meta-form">

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'data' => ['pjax' => true],
            'id' => 'teacher-form'
        ],
    ]);
    ?>

    <?php echo $form->field($model, 'group_id')->label('Группа')->dropDownList($model->getAllGroups());  ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Додати' : 'Оновити', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'studentbtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <p class="center"><a href="#">Переглянути графік відвідувань</a></p>

</div>
