<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin([
    // Pjax options
]);

if( Html::encode($operation) == 'created'):

    $coursesName = Html::encode($model->name);
    ?>

    <div class="alert alert-success">
        <p>Нова професія створена!</p>
        <p>Назва професії: <strong> <?= $coursesName ?></strong></p>
    </div>

<?php endif;

if( Html::encode($operation) == 'updated'):
    ?>

    <div class="alert alert-success">
        <p>Професія оновлена!</p>
    </div>

    <?php
endif;

$form = ActiveForm::begin([
    'options' => [
        'data' => ['pjax' => true],
        'id' => 'courses-form'
    ],
]);

?>
    <div class="col-xs-12 col-md-12">

        <?php
        if ($courses_status == 'create') {
            $nameValue = '';
        } else {
            $nameValue = $model->name;
        }
        ?>

        <?php
            echo $form->field($model, 'name')->label('Назва професії')->textInput([
            'placeholder' => 'Введіть назву професії',
            'value' =>$nameValue])
        ?>

    </div>

    <?php
    /*
    if($courses_status == 'create') {

        echo $form->field($model, 'subject', [
            'options' => [
                'class' => 'col-md-12'
            ]
        ])
            ->label('Оберіть предмети:')
            ->checkboxList($subjects, [
                'item' => function ($index, $label, $name, $checked, $value) {
                    return "<div class='checkbox col-md-4'><label><input type='checkbox' {$checked} name='{$name}' value='{$value}'>{$label}</label></div>";
                }
            ]);

    } else  {

        $coursesSubject = Html::encode($model->subject);

        $coursesSubjectArray = explode(", ", $coursesSubject);

        $checkedList = []; //Массив номеров выбранных элементов checkboxList

        for ($i=0; $i<count($subjects); $i++) {
            foreach ($coursesSubjectArray as $subject) {
                if($subjects[$i]==$subject) {
                    array_push($checkedList, $i);
                }
            }
        }

        $model->subject = $checkedList;

        echo $form->field($model, 'subject', ['options' => ['class' => 'col-md-12']])->label('Оберіть предмети')
            ->checkboxList($subjects, [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $checked = $checked ? 'checked' : '';
                    return "<div class='checkbox col-md-4'><label><input type='checkbox' {$checked} name='{$name}' value='{$label}'>{$label}</label></div>";
                }
            ]);

    }
    */
    ?>

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