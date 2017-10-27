<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Редагувати професію';
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['courses']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin([
    // Pjax options
]);

$form = ActiveForm::begin([
    'options' => ['data' => ['pjax' => true], 'id' => 'courses-form'],
]);
$subjects=array('Предмет 1', 'Предмет 2', 'Предмет 3', 'Предмет 4', 'Предмет 5', 'Предмет 6', 'Предмет 7', 'Предмет 8');
?>
    <div class="col-md-3">

        <?= $form->field($model, 'name')->label('Назва професії')->textInput(['placeholder' => 'Введіть назву професії']) ?>

        <?= $form->field($model, 'pract')->label('Кількість занять виробничої практики')->textInput(['type' => 'number', 'min' => '1']) ?>

        <?= $form->field($model, 'worklect')->label('Кількість занять виробничого навчання')->textInput(['type' => 'number', 'min' => '1']) ?>

        <?= $form->field($model, 'teorlect')->label('Кількість занять теоритичного навчання')->textInput(['type' => 'number', 'min' => '1']) ?>

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
        <?= Html::submitButton('Редагувати', ['class' => 'btn btn-primary']) ?>
    </div>

<?php
ActiveForm::end();
Pjax::end();
?>