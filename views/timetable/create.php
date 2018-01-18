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

    <?php
    // Parent
        //echo $form->field($model, 'audience_id')->label('Оберіть аудиторію')->dropDownList($model->getAudienceNames(), $options);
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
    ?>

    <?php
    ActiveForm::end();
    ?>

</div>
