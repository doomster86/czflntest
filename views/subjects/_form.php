<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin([
    // Pjax options
]);

if( Html::encode($operation) == 'created'):
    $subjectName = Html::encode($model->name);

?>

    <div class="alert alert-success">
        <p>Новий предмет створено!</p>
        <p>Назва предмету: <strong><?= $subjectName ?></p>
    </div>

    <?php
endif;

if( Html::encode($operation) == 'updated'):
    ?>

    <div class="alert alert-success">
        <p>Предмет оновлено!</p>
    </div>

    <?php
endif;

$form = ActiveForm::begin([
    'options' => ['data' => ['pjax' => true], 'id' => 'subjects-form'],
]);

?>

    <div class="col-md-12">

        <?php
        if ($subject_status == 'create') {
            $nameValue = '';
        } else {
            $nameValue = $model->name;
        }
        ?>

        <?= $form->field($model, 'name')->label('Назва предмету')->textInput([
            'placeholder' => 'Введіть назву предмету',
            'value' =>$nameValue])
        ?>
    </div>

    <div class="form-group col-md-12">
        <?php if($subject_status == 'create') {
            echo Html::submitButton('Створити', ['class' => 'btn btn-success']);
        } else {
            echo Html::submitButton('Оновити', ['class' => 'btn btn-primary']);
        } ?>
    </div>

<?php
ActiveForm::end();
Pjax::end();
?>
