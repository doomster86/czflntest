<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TimetableParts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="timetable-parts-form">

	<?php
	$form = ActiveForm::begin([
		'options' => [
			'data' => ['pjax' => true],
			'id' => 'user-form'
		],
	]);
	?>

	<?php
	//$datetime = strtotime('now');
	//echo $datetime;
	//echo Yii::$app->dtConverter->toDisplayDate($datetime);
	?>

	<?php
	$options = array(
		'options' =>  [
			0 => [
				'disabled' => true,
				'selected' => 'selected',
			],
			//1 => ...
		]
	);
	//['options' => [0 => ['disabled' => true]]]

    $formatter = new \yii\i18n\Formatter;
    $model->date = $formatter->asDate($model->date, "dd.MM.yyyy");
	?>

	<?=  $form->field($model, 'date')->label('Оберіть дату')->widget(DatePicker::className(),[]); ?>

	<?php echo $form->field($model, 'lecture_id')->label('Оберіть пару')->dropDownList($model->getLectureTime($model->corps_id), $options);  ?>

	<?php echo $form->field($model, 'audience_id')->label('Оберіть аудиторію')->dropDownList($model->getAudienceNames(), $options);  ?>

	<?php echo $form->field($model, 'teacher_id')->label('Оберіть викладача')->dropDownList($model->getTeachersNames(), $options);  ?>

	<?php echo $form->field($model, 'subjects_id')->label('Оберіть предмет')->dropDownList($model->getSubjectsNames(), $options);  ?>

	<?php echo $form->field($model, 'group_id')->label('Оберіть групу')->dropDownList($model->getGroupsNames(), $options);  ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Створити' : 'Редагувати', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	<?php
	ActiveForm::end();
	?>

</div>
