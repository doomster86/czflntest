<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Rank */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
Pjax::begin([
// Pjax options
]);
?>
<div class="rank-form">

    <?php if (Html::encode($status) == 'created') : ?>
        <div class="alert alert-success">
            <p>Додано нове звання. </p>
            <p>Назва: <strong><?= Html::encode($model->rank_name) ?></strong></p>
        </div>
    <?php endif; ?>

    <?php if (Html::encode($status) == 'updated') : ?>
        <div class="alert alert-success">
            <p>Звання оновлено.</p>
        </div>
    <?php endif; ?>

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'data' => [
                'pjax' => true,
            ],
            'id' => 'degree-form'
        ],
    ]);

    $nameValue = '';
    if (Html::encode($current_action) == 'update') {
        $nameValue = $model->rank_name;
    }

    ?>

    <?= $form->field($model, 'rank_name')->label('Введіть назву')->textInput(['maxlength' => true, 'value' => $nameValue]) ?>

    <div class="form-group">
        <?= Html::submitButton($current_action == 'create' ? 'Додати' : 'Оновити', ['class' => $current_action == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>
