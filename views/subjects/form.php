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
        $total = Html::encode($model->total);
        $max_week = Html::encode($model->max_week);
        ?>

        <div class="alert alert-success">
            <p>Новий предмет створено!</p>
            <p>Назва предмету: <strong><?= $name; ?></strong></p>
            <p>Викладач: <strong><?= $teacher; ?></strong></p>
            <p>Загальна кількість: <strong><?= $total; ?></strong></p>
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
        $totalValue = '';
        $max_weekValue = '';
    } else {
        //если update
        $nameValue = $model->name;
        $totalValue = $model->total;
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

    //Общее кол-во
    echo $form->field($model, 'total')->label('Загальна кількість')->textInput([
        'type' => 'number',
        'min' => '1',
        'placeholder' => 'Загальна кількість',
        'value' =>$totalValue]);

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
