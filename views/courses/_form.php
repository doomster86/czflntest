<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin([
    // Pjax options
]);
?>

<?php

$form = ActiveForm::begin([
    'options' => [
        'data' => [
            'pjax' => true
        ],
        'id' => 'course-form'
    ],
]);

?>
<div class="row">
    <div class="col-xs-6">
        <?php
        /*
            $subjects = [
              0 => 'Оберіть',
              1 => 'Матан',
              2 => 'Майнінг',
              3 => 'Фізика'
            ];
        */
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

        <?php echo $form->field($modelLessons, 'subject')->label('Оберіть предмет')->dropDownList($subjects, $options);  ?>
    </div>
    <div class="col-xs-6">
        <?php
        //Количество
        echo $form->field($modelLessons, 'quantity')
        ->label('Кількість занять')
        ->textInput([
            'type' => 'number',
            'min' => '0',
            'placeholder' => 'Кількість занять',
            //'value' =>$dur_lessonValue
        ]);
        ?>
    </div>
    <div class="form-group col-xs-12">
        <?= Html::submitButton('Створити', ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php

ActiveForm::end(); ?>

<?php Pjax::end(); ?>