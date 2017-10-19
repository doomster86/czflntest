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


<ul>
    <?php foreach ($courses as $cours): ?>
        <li>
            <?= Html::encode("{$cours->name} ({$cours->teacher_id})") ?>:
        </li>
    <?php endforeach; ?>
</ul>

<?= LinkPager::widget(['pagination' => $pagination]) ?>
