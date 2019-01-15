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

    $form = ActiveForm::begin([
        'options' => [
                'data' => [
                        'pjax' => true
                ],
            'id' => 'subjects-form'
        ],
    ]);


    //Название
    echo $form->field($model, 'title')->label('Назва предмету')->textInput([
        'min' => '1',
        'placeholder' => 'Введіть назву предмету',
        'value' =>$model->title,'readonly'=> true]);

    //Преподаватели
    echo $form->field($model->user, 'id')->label('Оберіть викладача')->dropDownList($model->getTeachersNames(), ['disabled' => 'disabled']);

    // Аудитории
    echo $form->field($model, 'audience_id')->label('Оберіть аудиторію')->dropDownList($model->getAudienceNames());


    // Аудитории
    echo $form->field($model, 'rnp_id')->label('Професія')->dropDownList($model->getProfessionNames(), ['disabled' => 'disabled']);

    //Макс. в неделю
    /*echo $form->field($model, 'max_week')->label('Макс. занять у тиждень')->textInput([
        'type' => 'number',
        'min' => '0',
        'placeholder' => 'Макс. занять у тиждень',
        'value' =>$max_weekValue]);
*/
    echo $form->field($model, 'required')->checkbox(['label' => 'Обов\'язкова аудиторія']);

    echo $form->field($model, 'practice')->checkbox(['label' => 'Виробниче навчання']);

    ?>

    <div class="form-group">
        <?= Html::submitButton($current_action == 'create' ? 'Створити' : 'Оновити', ['class' => $current_action == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>
