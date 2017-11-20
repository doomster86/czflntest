<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin([
    // Pjax options
]);

if( Html::encode($operation) == 'updated'):
    ?>

    <div class="alert alert-success">
        <p>Оновлено!</p>
    </div>

    <?php
endif;
?>

<div class="user-form">

    <?php
        $form = ActiveForm::begin([
            'options' => [
                'data' => ['pjax' => true],
                'id' => 'user-form'
            ],
        ]);
    ?>

    <?php echo $form->field($model, 'status')->label('Встановити статус')->dropDownList($status);  ?>

    <?php echo $form->field($model, 'role')->label('Обрати роль')->dropDownList($roles);  ?>

    <div class="form-group">
        <?= Html::submitButton('Оновити', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php
        ActiveForm::end();
        Pjax::end();
    ?>

</div>
