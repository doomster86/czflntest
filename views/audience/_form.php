<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;

use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use \app\models\Corps;
use \app\controllers\AudienceController;
/* @var $this yii\web\View */
/* @var $model app\models\Audience */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="audience-form">
    <?php
    Pjax::begin([
    // Pjax options
    ]);
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'num')->textInput(['maxlength' => true]) ?>




    <?php

        //AudienceController::v($corps);
        $options = array(
                'options' =>  [
                        0 => [
                                'disabled' => true,
                                //'' => '',
                        ],
                        //1 => ...
                ]
        );
        //['options' => [0 => ['disabled' => true]]]
    ?>

    <?php //echo $form->field($model, 'corps')->label('Оберіть корпус')->dropDownList($corps, $options);  ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Створити' : 'Оновити', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
