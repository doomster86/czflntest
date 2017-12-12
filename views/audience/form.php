<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
//use yii\widgets\ActiveForm;
use \app\models\Corps;
use \app\controllers\AudienceController;
/* @var $this yii\web\View */
/* @var $model app\models\Audience */
/* @var $form yii\widgets\ActiveForm */
Pjax::begin([
    // Pjax options
]);

?>

<div class="audience-form">

    <?php if (Html::encode($status_form) == 'created') : ?>
        <div class="alert alert-success">
            <p>Додано нову аудиторію. </p>
            <p>Назва: <strong><?= Html::encode($model->name) ?></strong></p>
            <p>Номер: <strong><?= Html::encode($model->num) ?></strong></p>
            <p>Корпус: <strong><?= $model->getCorpsNameByAudienceID($model->ID); ?></strong></p>
        </div>
    <?php endif; ?>

    <?php if (Html::encode($status_form) == 'updated') : ?>
        <div class="alert alert-success">
            <p>Аудиторію оновлено.</p>
        </div>
    <?php endif; ?>

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'data' => [
                'pjax' => true,
                //'data-pjax' => '0',
            ],
            'id' => 'audience-form'
        ],
    ]);

    $nameValue = '';
    $numValue = '';
    if (Html::encode($current_action) == 'update') {
        $nameValue = $model->name;
        $numValue = $model->num;
    }

    ?>
    <?= $form->field($model, 'name')
        ->label('Назва аудиторії')
        ->textInput([
                'placeholder' => 'Введіть назву',
                'value' => $nameValue
        ]); ?>

    <?= $form->field($model, 'num')
        ->label('Номер аудиторії')
        ->textInput([
                'placeholder' => 'Введіть номер',
	            'value' => $numValue
        ]) ?>

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
    ?>

    <?php echo $form->field($model, 'corps_id')->label('Оберіть корпус')->dropDownList($model->getCorpsNames(), $options);  ?>

    <div class="form-group">
	    <?= Html::submitButton($current_action == 'create' ? 'Додати' : 'Оновити', ['class' => $current_action == 'create' ? 'btn btn-success' : 'btn btn-primary', /*'data-pjax' => '0',*/]) ?>

    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>