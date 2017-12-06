<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
//use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Degree */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
Pjax::begin([
// Pjax options
]);
?>
<div class="degree-form">

    <?php if (Html::encode($status) == 'created') : ?>
        <div class="alert alert-success">
            <p>Додано нову ступінь. </p>
            <p>Назва: <strong><?= Html::encode($model->degree_name) ?></strong></p>
        </div>
    <?php endif; ?>

    <?php if (Html::encode($status) == 'updated') : ?>
        <div class="alert alert-success">
            <p>Ступінь оновлено.</p>
        </div>
    <?php endif; ?>

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'data' => [
                'pjax' => true,
                //'data-pjax' => '0',
            ],
            'id' => 'degree-form'
        ],
    ]);

    $nameValue = '';
    if (Html::encode($current_action) == 'update') {
        $nameValue = $model->degree_name;
    }
    ?>

    <?= $form->field($model, 'degree_name')->label('Введіть назву ступені')->textInput(['maxlength' => true, 'value' => $nameValue]) ?>

    <div class="form-group">
        <?= Html::submitButton($current_action == 'create' ? 'Додати' : 'Оновити', ['class' => $current_action == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>
