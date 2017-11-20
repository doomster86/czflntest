<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;

use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
//use \app\models\Courses;
//use \app\controllers\GroupsController;
/* @var $this yii\web\View */
/* @var $model app\models\Groups */
/* @var $form yii\widgets\ActiveForm */

Pjax::begin([
// Pjax options
]);

?>

<div class="groups-form">

	<?php if (Html::encode($status) == 'created') : ?>
        <div class="alert alert-success">
            <p>Додано нову групу.</p>
            <p>Назва: <strong><?= Html::encode($model->name) ?></strong></p>
            <p>Професія: <strong><?= Html::encode($courses[$model->course]) ?></strong></p>
            <p>Куратор: <strong><?= Html::encode($curators[$model->curator]) ?></strong></p>
        </div>
	<?php endif; ?>

	<?php if (Html::encode($status) == 'updated') : ?>
        <div class="alert alert-success">
            <p>Групу оновлено.</p>
        </div>
	<?php endif; ?>

    <?php
    $form = ActiveForm::begin([
	    'options' => [
		    'data' => [
			    'pjax' => true,
			    //'data-pjax' => '0',
		    ],
		    'id' => 'groups-form',
	    ],

    ]);

    $nameValue = '';
    if (Html::encode($current_action) == 'update') {
	    $nameValue = $model->name;
    }

    ?>

    <div class="row">

        <div class="col-xs-4">
            <?= $form->field($model, 'name')
                ->textInput([
                    'maxlength' => true,
                    'value' => $nameValue
                ])
            ?>
        </div>

        <div class="col-xs-4">

            <?php
            $options = array(

                'options' =>  [
                    0 => [
                        'disabled' => true,
                        'selected' => 'selected',
                    ],

                ]

            );
            //['options' => [0 => ['disabled' => true]]]
            ?>

            <?php echo $form->field($model, 'course')
                ->label('Оберіть професію')
                ->dropDownList($courses, $options);  ?>

        </div>
        <div class="col-xs-4">

		    <?php
		    $options = array(

			    'options' =>  [
				    0 => [
					    'disabled' => true,
					    'selected' => 'selected',
				    ],

			    ]

		    );
		    //['options' => [0 => ['disabled' => true]]]
		    ?>

		    <?php echo $form->field($model, 'curator')
			    ->label('Оберіть професію')
			    ->dropDownList($curators, $options);  ?>

        </div>

    </div>

    <div class="row">
        <div class="col-xs-12">

	        <?= Html::submitButton($current_action == 'create' ? 'Додати' : 'Оновити', ['class' => $current_action == 'create' ? 'btn btn-success' : 'btn btn-primary',]) ?>
            <?php /*
	        <?= Html::submitButton('Додати', [
                'class' => 'btn btn-success' ,
                'data-pjax' => '0',
            ]) ?>
            */ ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>