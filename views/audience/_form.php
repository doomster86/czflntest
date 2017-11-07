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
            <p>Корпус: <strong><?= Html::encode($corps[$model->corps]) ?></strong></p>
        </div>
    <?php endif; ?>

    <?php if (Html::encode($status_form) == 'updated') : ?>
        <div class="alert alert-success">
            <p>Аудиторію оновлено.</p>
        </div>
    <?php endif; ?>

    <?php
    $form = ActiveForm::begin([
        'options' => ['data' => ['pjax' => true], 'id' => 'audience-form'],
    ]);
    ?>
    <?= $form->field($model, 'name')->label('Назва аудиторії')->textInput(['placeholder' => 'Введіть назву']); ?>

    <?= $form->field($model, 'num')->label('Номер аудиторії')->textInput(['placeholder' => 'Введіть номер']) ?>

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

    <?php echo $form->field($model, 'corps')->label('Оберіть корпус')->dropDownList($corps, $options);  ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Створити' : 'Оновити', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>