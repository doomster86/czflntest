<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin([
    // Pjax options
]);
?>



    <div class="row">
        <div class="col-xs-12">
            <?php if ($status == 'added') : ?>
                <div class="alert alert-success">
                    <p>Практика додана!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php

$form = ActiveForm::begin([
    'options' => [
        'data' => [
            'pjax' => true,
        ],
        'id' => 'practice-form',
    ],

]);

?>
    <div class="row">
        <div class="col-xs-6">
            <?php
            $options = array(

                'options' =>  [
                    0 => [
                        'disabled' => true,
                        'selected' => 'selected',
                    ],

                ]

            );
            ?>

            <?php echo $form->field($modelPracticeLessons, 'practice_id')
                ->label('Оберіть виробничу практику')
                ->dropDownList($practice, $options);  ?>
        </div>
        <div class="col-xs-6">
            <?php
            //Количество

            echo $form->field($modelPracticeLessons, 'quantity')
                ->label('Кількість занять')
                ->textInput([
                    'type' => 'number',
                    'min' => '0',
                    'placeholder' => 'Кількість занять',
                    'value' => ''
                ]);
            ?>
        </div>

    </div>

    <div class="row">
        <div class="form-group col-xs-12">
            <?= Html::submitButton('Додати', [
                'class' => 'btn btn-success' ,
                //'data-pjax' => '0',
            ]) ?>
        </div>
    </div>

<?php

ActiveForm::end(); ?>

<?php Pjax::end(); ?>