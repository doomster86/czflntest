<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Timetable */

$this->title = 'Створити заняття';
?>
<div class="timetable-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'data' => ['pjax' => true],
            'id' => 'create-form'
        ],
    ]);
    ?>

    <?=  $form->field($model, 'date')->label('Оберіть дату')->widget(DatePicker::className(),[]); ?>

    <?php
    // Parent
    echo $form->field($model, 'corps_id')->label('Оберіть корпус')->dropDownList($model->getCorpsNames(), [
        'id'=>'corps_id',
        'prompt'=>'Оберіть корпус'
    ]);

    // Child # 1
    echo $form->field($model, 'audience_id')->label('Оберіть аудиторію')->widget(DepDrop::classname(), [
        'options'=>['id'=>'audience_id'],
        'pluginOptions'=>[
            'depends'=>['corps_id'],
            'placeholder'=>'Оберіть аудиторію',
            'url'=>Url::to(['/timetable/subcat'])
        ]
    ]);

    // Child # 2
    echo $form->field($model, 'lecture_id')->label('Оберіть пару')->widget(DepDrop::classname(), [
        'options'=>['id'=>'lecture_id'],
        'pluginOptions'=>[
            'depends'=>['corps_id'],
            'placeholder'=>'Оберіть пару',
            'url'=>Url::to(['/timetable/subcatlecture'])
        ]
    ]);
    ?>

    <?php
    // Parent
    echo $form->field($model, 'group_id')->label('Оберіть групу')->dropDownList($model->getGroupsNames(), [
        'id'=>'group_id',
        'prompt'=>'Оберіть групу'
    ]);

    // Child # 1
    echo $form->field($model, 'subjects_id')->label('Оберіть предмет')->widget(DepDrop::classname(), [
        'options'=>['id'=>'subjects_id'],
        'pluginOptions'=>[
            'depends'=>['group_id'],
            'placeholder'=>'Оберіть предмет',
            'url'=>Url::to(['/timetable/subcatsubjects'])
        ]
    ]);

    ?>

    <?php
    ActiveForm::end();
    ?>

</div>
