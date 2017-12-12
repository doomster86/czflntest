<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin([
    // Pjax options
]);
?>

<div class="subjects-form">

    <?php if( Html::encode($status_form) == 'created'): ?>

        <div class="alert alert-success">
            <p>Новий предмет створено!</p>
            <p>Назва предмету: <strong><?= Html::encode($model->name); ?></strong></p>
            <p>Викладач: <strong><?= $model->getTeacherNameById($model->teacher_id) ?></strong></p>
            <p>Макс. на тиждень: <strong><?= Html::encode($model->max_week); ?></strong></p>
        </div>
    <?php endif;

    if( Html::encode($status_form) == 'updated'):
        ?>
        <div class="alert alert-success">
            <p>Предмет оновлено!</p>
        </div>
        <?php
    endif;

    $form = ActiveForm::begin([
        'options' => [
                'data' => [
                        'pjax' => true
                ],
            'id' => 'subjects-form'
        ],
    ]);
    ?>

    <?php
    if ($current_action == 'create') {
        $nameValue = '';
        $max_weekValue = '';
    } else {
        //если update
        $nameValue = $model->name;
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

    echo $form->field($model, 'teacher_id')->label('Оберіть викладача')->dropDownList($model->getTeachersNames(), $options);

    echo $form->field($model, 'audience_id')->label('Оберіть аудиторію')->dropDownList($model->getAudienceNames(), $options);

    //Макс. в неделю
    echo $form->field($model, 'max_week')->label('Макс. у тиждень')->textInput([
        'type' => 'number',
        'min' => '0',
        'placeholder' => 'Макс. у тиждень',
        'value' =>$max_weekValue]);

    echo $form->field($model, 'required')->checkbox(['label' => 'Обов\'язкова аудиторія']);

    ?>

    <div class="form-group">
        <?= Html::submitButton($current_action == 'create' ? 'Створити' : 'Оновити', ['class' => $current_action == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>
