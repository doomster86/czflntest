<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

$this->title = 'Створити нову професію';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
    Pjax::begin([
    // Pjax options
    ]);
        if (!empty($model->name)):
?>

            <p>Нова професія створена!</p>

            <ul>
                <li><label>Назва професії: </label>: <?= Html::encode($model->name) ?></li>
                <li><label>Група</label>: <?= Html::encode($model->group) ?></li>
                <li><label>Кількість занять виробничої практики</label>: <?= Html::encode($model->pract) ?></li>
                <li><label>Кількість занять виробничого навчання</label>: <?= Html::encode($model->worklect) ?></li>
                <li><label>Кількість занять теоритичного навчання</label>: <?= Html::encode($model->teorlect) ?></li>
            </ul>

    <?php
        endif;
        $form = ActiveForm::begin([
            'options' => ['data' => ['pjax' => true]],
        ]);
    ?>

            <?= $form->field($model, 'name')->label('Назва професії')->textInput(['placeholder' => 'Введіть назву професії']) ?>

            <?= $form->field($model, 'group')->label('Група')->dropDownList(['1','2']) ?>

            <?= $form->field($model, 'pract')->label('Кількість занять виробничої практики')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

            <?= $form->field($model, 'worklect')->label('Кількість занять виробничого навчання')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

            <?= $form->field($model, 'teorlect')->label('Кількість занять теоритичного навчання')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

            <?= $form->field($model, 'subject')->label('Оберіть предмети')->checkboxList(['П1', 'П2', 'П3', 'П4', 'П5', 'П6', 'П7', 'П8']); ?>

            <div class="form-group">
                <?= Html::submitButton('Створити', ['class' => 'btn btn-primary']) ?>
            </div>

<?php
        ActiveForm::end();
    Pjax::end();
?>

<table class="table table-striped table-bordered">
    <tr>
        <th>Назва курсу</th>
        <th>Група</th>
        <th>Кількість занять виробничої практики</th>
        <th>Кількість занять виробничого навчання</th>
        <th>Кількість занять теоритичного навчання</th>
        <th>Предмети</th>
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
