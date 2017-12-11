<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;

use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use \app\models\Corps;
use \app\controllers\AudienceController;
/* @var $this yii\web\View */
/* @var $model app\models\Corps */
/* @var $form yii\widgets\ActiveForm */

Pjax::begin([
    // Pjax options
]);

?>

<div class="corps-form">

    <?php if (Html::encode($status_form) == 'created') : ?>
        <div class="alert alert-success">
            <p>Додано новий корпус. </p>
            <p>Назва: <strong><?= Html::encode($model->corps_name) ?></strong></p>
            <p>Розташування: <strong><?= Html::encode($model->location) ?></strong></p>
        </div>
    <?php endif; ?>

    <?php if (Html::encode($status_form) == 'updated') : ?>
        <div class="alert alert-success">
            <p>Корпус оновлено.</p>
        </div>
    <?php endif; ?>

    <?php
    $nameValue = '';
    $nameLocation = '';

    if (Html::encode($current_action) == 'update') {
        $nameValue = $model->corps_name;
        $nameLocation = $model->location;
    }
    $form = ActiveForm::begin([
    'options' => ['data' => ['pjax' => true], 'id' => 'courses-form'],
    ]);
    ?>
    <?= $form->field($model, 'corps_name')->textInput(['placeholder' => 'Введіть назву', 'value' => $nameValue]) ?>

    <?= $form->field($model, 'location')->textInput(['placeholder' => 'Введіть розташування', 'value' => $nameLocation]) ?>

    <div class="form-group">
        <?= Html::submitButton($current_action == 'create' ? 'Додати' : 'Оновити', ['class' => $current_action == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>