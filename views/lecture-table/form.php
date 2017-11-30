<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
/* @var $this yii\web\View */
/* @var $model app\models\LectureTable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lecture-table-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php

    echo $form->field($model, 'time_start')->widget(TimePicker::classname(), [
        'pluginOptions' => [
            'showSeconds' => true,
            'showMeridian' => false,
            'minuteStep' => 1,
            'secondStep' => 10,
        ]
    ]);

    echo $form->field($model, 'time_stop')->widget(TimePicker::classname(), [
        'pluginOptions' => [
            'showSeconds' => true,
            'showMeridian' => false,
            'minuteStep' => 1,
            'secondStep' => 10,
        ]
    ]);

    ?>

    <?= $form->field($model, 'corps')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
