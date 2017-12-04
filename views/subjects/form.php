<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin([
    // Pjax options
]);
?>

<div class="subjects-form">

    <?php
    if( Html::encode($status_form) == 'created'):
        $name = Html::encode($model->name);
        $teacher = Html::encode($model->teacher);
        $dur_lesson = Html::encode($model->dur_lesson);
        $dur_break = Html::encode($model->dur_break);
        $max_week = Html::encode($model->max_week);
        ?>

        <div class="alert alert-success">
            <p>Новий предмет створено!</p>
            <p>Назва предмету: <strong><?= $name; ?></strong></p>
            <p>Викладач: <strong><?= $teacher; ?></strong></p>
            <p>Тривалість заняття: <strong><?= $dur_lesson; ?></strong></p>
            <p>Тривалість перерви: <strong><?= $dur_break; ?></strong></p>
            <p>Макс. на тиждень: <strong><?= $max_week; ?></strong></p>
        </div>

        <?php
    endif;

    if( Html::encode($status_form) == 'updated'):
        ?>
        <div class="alert alert-success">
            <p>Предмет оновлено!</p>
        </div>
        <?php
    endif;

    $form = ActiveForm::begin([
        'options' => ['data' => ['pjax' => true], 'id' => 'subjects-form'],
    ]);

    ?>

    <?php
    if ($current_action == 'create') {
        $nameValue = '';
        $dur_lessonValue = 45;
        $dur_breakValue = 5;
        $max_weekValue = '';
    } else {
        //если update
        $nameValue = $model->name;
        $dur_lessonValue = $model->dur_lesson;
        $dur_breakValue = $model->dur_break;
        $max_weekValue = $model->max_week;
    }
    ?>

    <?php

    //Название
    echo $form->field($model, 'name')->label('Назва предмету')->textInput([
        'min' => '1',
        'placeholder' => 'Введіть назву предмету',
        'value' =>$nameValue]);

    //Преподаватели
    $options = array(
        'options' =>  [
            0 => [
                'disabled' => true,
                'selected' => 'selected',
                //'' => '',
            ],
            //1 => ...
        ]
    );


    echo $form->field($model, 'teacher')->label('Оберіть викладача')->dropDownList($model->getTeachersNames(), $options);

    //Продолжительность занятия
    echo $form->field($model, 'dur_lesson')->label('Тривалість заняття')->textInput([
        'type' => 'number',
        'min' => '1',
        'placeholder' => 'Тривалість заняття',
        'value' =>$dur_lessonValue]);

    //Продолжительность перерыва
    echo $form->field($model, 'dur_break')->label('Тривалість перерви')->textInput([
        'type' => 'number',
        'min' => '1',
        'placeholder' => 'Тривалість перерви',
        'value' =>$dur_breakValue]);

    //Макс. в неделю
    echo $form->field($model, 'max_week')->label('Макс. у тиждень')->textInput([
        'type' => 'number',
        'min' => '0',
        'placeholder' => 'Макс. у тиждень',
        'value' =>$max_weekValue]);
    ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Створити' : 'Оновити', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>
