<?php
//use Yii;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
//use app\models\Lessons;
use yii\web\Controller;

Pjax::begin([
    // Pjax options
]);

$form = ActiveForm::begin([
    'options' => [
        'data' => [
            'pjax' => true,
            //'data-pjax' => '0',
        ],
        'id' => 'course-form',
    ],

]);
?>

    <div class="row">
        <div class="col-xs-12">
            <?php if ($status == 'updated') : ?>
                <div class="alert alert-success">
                    <p>Кількість занять оновлена!</p>
                </div>
            <?php endif; ?>

            <?php

            //echo $myid;
            ?>

        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?php
            //Количество

            echo $form->field($model, 'quantity')
                ->label('Кількість занять')
                ->textInput([
                    'type' => 'number',
                    'min' => '0',
                    'placeholder' => 'Кількість занять',
                    'value' => $quantity
                ]);
            ?>
        </div>
        <div class="form-group col-xs-12">
            <?= Html::submitButton('Оновити', [
                'class' => 'btn btn-primary' ,
                //'data-pjax' => '0',
            ]) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>