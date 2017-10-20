<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Створити новий курс';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->label('Назва курсу')->textInput(['placeholder' => 'Введіть назву курсу']) ?>

<?= $form->field($model, 'teacher')->label('Викладач')->dropDownList(['Іван','Не Іван']) ?>

<?= $form->field($model, 'group')->label('Група')->dropDownList(['1','2']) ?>

<?= $form->field($model, 'pract')->label('Кількість занять виробничої практики')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

<?= $form->field($model, 'worklect')->label('Кількість занять виробничого навчання')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

<?= $form->field($model, 'teorlect')->label('Кількість занять теоритичного навчання')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

    <div class="form-group">
        <?= Html::submitButton('Створити', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

<table class="table table-striped table-bordered">
    <tr>
        <th>Назва курсу</th>
        <th>Викладач</th>
        <th>Група</th>
        <th>Кількість занять виробничої практики</th>
        <th>Кількість занять виробничого навчання</th>
        <th>Кількість занять теоритичного навчання</th>
    </tr>
    <?php foreach ($courses as $cours): ?>
    <tr>
        <td><?= Html::encode("{$cours->name}") ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <?php endforeach; ?>
</table>

<?= LinkPager::widget(['pagination' => $pagination]) ?>
