<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use \app\models\Lessons;

Pjax::begin([
    // Pjax options
]);
?>


<?php
$form = ActiveForm::begin([
    'options' => ['data' => ['pjax' => true], 'id' => 'course-form'],
]);

?>

    <?php

    $subjects = [
      0 => 'Оберіть',
      1 => 'Матан',
      2 => 'Майнінг',
      3 => 'Фізика'
    ];

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

    <?php echo $form->field($model, 'subject')->label('Оберіть предмет')->dropDownList($subjects, $options);  ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Створити' : 'Оновити', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php

ActiveForm::end(); ?>

<?php Pjax::end(); ?>