<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin([
    // Pjax options
]);

if( Html::encode($operation) == 'created'):

    $coursesName = Html::encode($model->name);
    $coursesPract = Html::encode($model->pract);
    $coursesWorklect = Html::encode($model->worklect);
    $coursesTeorlect = Html::encode($model->teorlect);
    $coursesSubject = Html::encode($model->subject);

    ?>

    <div class="alert alert-success">
        <p>Нова професія створена!</p>

        <ul>
            <li><label>Назва професії: </label>: <?= $coursesName ?></li>
            <li><label>Кількість занять виробничої практики</label>: <?= $coursesPract ?></li>
            <li><label>Кількість занять виробничого навчання</label>: <?= $coursesWorklect ?></li>
            <li><label>Кількість занять теоритичного навчання</label>: <?= $coursesTeorlect ?></li>
            <li><label>Предмети: </label>: <?= $coursesSubject ?></li>
        </ul>
    </div>

    <?php
endif;

if( Html::encode($operation) == 'updated'):
    ?>

    <div class="alert alert-success">
        <p>Професія оновлена!</p>
    </div>

    <?php
endif;

$form = ActiveForm::begin([
    'options' => ['data' => ['pjax' => true], 'id' => 'courses-form'],
]);

$subjects=array('Предмет 1', 'Предмет 2', 'Предмет 3', 'Предмет 4', 'Предмет 5', 'Предмет 6', 'Предмет 7', 'Предмет 8');

?>
    <div class="col-md-3">

        <?php if (!empty($model->name) && $courses_status == 'create'): ?>

            <?= $form->field($model, 'name')->label('Назва професії')->textInput(['placeholder' => 'Введіть назву професії', 'value' =>'']) ?>

        <?php else: ?>

            <?= $form->field($model, 'name')->label('Назва професії')->textInput(['placeholder' => 'Введіть назву професії']) ?>

        <?php endif; ?>

        <?= $form->field($model, 'pract')->label('Кількість занять виробничої практики')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

        <?= $form->field($model, 'worklect')->label('Кількість занять виробничого навчання')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

        <?= $form->field($model, 'teorlect')->label('Кількість занять теоритичного навчання')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

    </div>
    <div class="col-md-9">

        <?= $form->field($model, 'subject', ['options' => ['class' => 'col-md-12']])->label('Оберіть предмети')
            ->checkboxList($subjects, [
                'item'=>function ($index, $label, $name, $checked, $value){
                    return "<div class='checkbox col-md-4'><label><input type='checkbox' {$checked} name='{$name}' value='{$label}'>{$label}</label></div>";
                }
            ]);
        ?>

    </div>

    <div class="form-group col-md-12">
        <?php if($courses_status == 'create') {
            echo Html::submitButton('Створити', ['class' => 'btn btn-success']);
        } else {
            echo Html::submitButton('Оновити', ['class' => 'btn btn-primary']);
        } ?>
    </div>

<?php
ActiveForm::end();
Pjax::end();
?>