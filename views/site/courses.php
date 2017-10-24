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
            'options' => ['data' => ['pjax' => true], 'id' => 'courses-form'],
        ]);
        $subjects=array('Предмет 1', 'Предмет 2', 'Предмет 3', 'Предмет 4', 'Предмет 5', 'Предмет 6', 'Предмет 7', 'Предмет 8');
    ?>

            <?= $form->field($model, 'name', ['options' => ['class' => 'col-sm-6']])->label('Назва професії')->textInput(['placeholder' => 'Введіть назву професії']) ?>

            <?= $form->field($model, 'group', ['options' => ['class' => 'col-sm-6']])->label('Група')->dropDownList(['1','2']) ?>

            <?= $form->field($model, 'pract', ['options' => ['class' => 'col-sm-4']])->label('Кількість занять виробничої практики')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

            <?= $form->field($model, 'worklect', ['options' => ['class' => 'col-sm-4']])->label('Кількість занять виробничого навчання')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

            <?= $form->field($model, 'teorlect', ['options' => ['class' => 'col-sm-4']])->label('Кількість занять теоритичного навчання')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

            <?= $form->field($model, 'subject', ['options' => ['class' => 'col-sm-12']])->label('Оберіть предмети')
                ->checkboxList($subjects, [
                    'item'=>function ($index, $label, $name, $checked, $value){
                        return "<div class='checkbox col-sm-4'><label><input type='checkbox' {$checked} name='{$name}' value='{$value}'>{$label}</label></div>";
                    }
                ]); ?>

            <div class="form-group col-sm-12">
                <?= Html::submitButton('Створити', ['class' => 'btn btn-primary']) ?>
            </div>

<?php
        ActiveForm::end();
    Pjax::end();
?>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Назва курсу</th>
            <th>Група</th>
            <th>Кількість занять виробничої практики</th>
            <th>Кількість занять виробничого навчання</th>
            <th>Кількість занять теоритичного навчання</th>
            <th>Предмети</th>
        </tr>
    </thead>
    <tbody>
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
    </tbody>
</table>

<?= LinkPager::widget(['pagination' => $pagination]) ?>
