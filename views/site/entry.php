<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->label('Ваше ім`я') ?>

<?= $form->field($model, 'email')->label('Ваш Email') ?>

    <div class="form-group">
        <?= Html::submitButton('Відправити', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>