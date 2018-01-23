<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Skill */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
Pjax::begin([
// Pjax options
]);
?>
<div class="skill-form">

    <?php if (Html::encode($status) == 'created') : ?>
        <div class="alert alert-success">
            <p>Додано нову категороію. </p>
            <p>Назва: <strong><?= Html::encode($model->skill_name) ?></strong></p>
        </div>
    <?php endif; ?>

    <?php if (Html::encode($status) == 'updated') : ?>
        <div class="alert alert-success">
            <p>Категорію оновлено.</p>
        </div>
    <?php endif; ?>

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'data' => [
                'pjax' => true,
            ],
            'id' => 'skill-form'
        ],
    ]);

    $nameValue = '';
    if (Html::encode($current_action) == 'update') {
        $nameValue = $model->skill_name;
    }

    ?>

    <?= $form->field($model, 'skill_name')->label('Введіть назву')->textInput(['maxlength' => true, 'value' => $nameValue]) ?>

    <div class="form-group">
        <?= Html::submitButton($current_action == 'create' ? 'Додати' : 'Оновити', ['class' => $current_action == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>
