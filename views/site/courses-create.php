<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use app\models\Courses;

$this->title = 'Створити нову професію';
$this->params['breadcrumbs'][] = $this->title;

    Pjax::begin([
        // Pjax options
    ]);
        if (!empty($model->name)):

            $coursesName = Html::encode($model->name);
            $coursesGroup = Html::encode($model->group);
            $coursesPract = Html::encode($model->pract);
            $coursesWorklect = Html::encode($model->worklect);
            $coursesTeorlect = Html::encode($model->teorlect);
            $coursesSubject='';
            foreach ($model->subject as $subject) {
                $coursesSubject = $coursesSubject . Html::encode($subject." ");
            }

            ?>

            <p>Нова професія створена!</p>

            <ul>
                <li><label>Назва професії: </label>: <?= $coursesName ?></li>
                <li><label>Група</label>: <?= $coursesGroup ?></li>
                <li><label>Кількість занять виробничої практики</label>: <?= $coursesPract ?></li>
                <li><label>Кількість занять виробничого навчання</label>: <?= $coursesWorklect ?></li>
                <li><label>Кількість занять теоритичного навчання</label>: <?= $coursesTeorlect ?></li>
                <li><label>Предмети: </label>: <?= $coursesSubject ?></li>
            </ul>

            <?php
            $newcours = new Courses();

            $newcours->name = $coursesName;
            $newcours->group_id = $coursesGroup;
            $newcours->pract = $coursesPract;
            $newcours->worklect = $coursesWorklect;
            $newcours->teorlect = $coursesTeorlect;
            $newcours->subject = $coursesSubject;

            $newcours->save();
            ?>

            <?php
        endif;
        $form = ActiveForm::begin([
            'options' => ['data' => ['pjax' => true], 'id' => 'courses-form'],
        ]);
        $subjects=array('Предмет 1', 'Предмет 2', 'Предмет 3', 'Предмет 4', 'Предмет 5', 'Предмет 6', 'Предмет 7', 'Предмет 8');
    ?>

            <?= $form->field($model, 'name', ['options' => ['class' => 'col-sm-6']])->label('Назва професії')->textInput(['placeholder' => 'Введіть назву професії', 'value' =>'']) ?>

            <?= $form->field($model, 'group', ['options' => ['class' => 'col-sm-6']])->label('Група')->dropDownList(['1','2']) ?>

            <?= $form->field($model, 'pract', ['options' => ['class' => 'col-sm-4']])->label('Кількість занять виробничої практики')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

            <?= $form->field($model, 'worklect', ['options' => ['class' => 'col-sm-4']])->label('Кількість занять виробничого навчання')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

            <?= $form->field($model, 'teorlect', ['options' => ['class' => 'col-sm-4']])->label('Кількість занять теоритичного навчання')->textInput(['type' => 'number', 'min' => '1', 'value' =>'1']) ?>

            <?= $form->field($model, 'subject', ['options' => ['class' => 'col-sm-12']])->label('Оберіть предмети')
    ->checkboxList($subjects, [
        'item'=>function ($index, $label, $name, $checked, $value){
            return "<div class='checkbox col-sm-4'><label><input type='checkbox' {$checked} name='{$name}' value='{$label}'>{$label}</label></div>";
        }
    ]); ?>

    <div class="form-group col-sm-12">
        <?= Html::submitButton('Створити', ['class' => 'btn btn-primary']) ?>
    </div>

<?php
        ActiveForm::end();
        Pjax::end();
?>