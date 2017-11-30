<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
//use app\models\Corps;
use kartik\time\TimePicker;
/* @var $this yii\web\View */
/* @var $model app\models\LectureTable */
/* @var $form yii\widgets\ActiveForm */

Pjax::begin([
// Pjax options
]);

?>

<div class="lecture-table-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    /*
    echo $form->field($model, 'time_start')->label('Час початку')->widget(TimePicker::widget([

	    'pluginOptions' => [
		    'showSeconds' => false,
		    'showMeridian' => false,
		    'minuteStep' => 1,
	    ]
    ]));
    */
    ?>

	<?php echo $form->field($model, 'time_start')->label('Час початку')->textInput();?>
	<?php echo $form->field($model, 'time_stop')->label('Час закінчення')->textInput();?>
    <?php
    /*
    echo $form->field($model, 'time_stop')->label('Час закінчення')->widget(TimePicker::widget([

	    'pluginOptions' => [
		    'showSeconds' => false,
		    'showMeridian' => false,
		    'minuteStep' => 1,
	    ]
    ]));
    */
    ?>

    <?= $form->field($model, 'corps_id')->label('Корпус')->dropDownList($model->getCorpsNames()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Додати' : 'Оновити', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php Pjax::end(); ?>