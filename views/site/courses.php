<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Создать новый курс';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->label('Название курса')->textInput(['placeholder' => 'Введите название курса']) ?>

<?= $form->field($model, 'teacher')->label('Преподаватель')->dropDownList(['Иван','Не Иван']) ?>

<?= $form->field($model, 'group')->label('Группа')->dropDownList(['1','2']) ?>

<?= $form->field($model, 'lections')->label('Кол-во лекций')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

<?= $form->field($model, 'practics')->label('Кол-во ПЗ')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
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
