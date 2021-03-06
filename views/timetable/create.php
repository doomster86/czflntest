<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Timetable */

$this->title = 'Створити заняття';
?>
<div class="timetable-create">

    <?php
    $request = Yii::$app->request;
    $formatter = new \yii\i18n\Formatter;
    $requestDate = $request->get('date');
    $requestY = $request->get('y');
    $date = $formatter->asDate($requestDate, "dd.MM.yyyy");
    //$model->date = $date;
    ?>

    <h1>
        <?php
        echo Html::encode($this->title);
        echo " ".$date." (".$requestY." пара)";
        ?>
    </h1>

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'data' => ['pjax' => true],
            'id' => 'create-form'
        ],
    ]);
    ?>

    <?
    //echo $form->field($model, 'date')->label('Оберіть дату')->widget(DatePicker::className(),[]);
    ?>

    <?php
    // Parent
    echo $form->field($model, 'corps_id')->label('Оберіть корпус')->dropDownList($model->getCorpsNames(), [
        'id'=>'corps_id',
        'prompt'=>'Оберіть корпус'
    ]);

    // Child # 1
    echo $form->field($model, 'audience_id')->label('Оберіть аудиторію')->widget(DepDrop::classname(), [
        'options'=>['id'=>'audience_id'],
        'pluginOptions'=>[
            'depends'=>['corps_id'],
            'placeholder'=>'Оберіть аудиторію',
            'url'=>Url::to(['/timetable/subcat'])
        ]
    ]);

    // Child # 2
    /*
    echo $form->field($model, 'lecture_id')->label('Оберіть пару')->widget(DepDrop::classname(), [
        'options'=>['id'=>'lecture_id'],
        'pluginOptions'=>[
            'depends'=>['corps_id'],
            'placeholder'=>'Оберіть пару',
            'url'=>Url::to(['/timetable/subcatlecture'])
        ]
    ]);
    */
    ?>

    <?php
    // Parent
    echo $form->field($model, 'group_id')->label('Оберіть групу')->dropDownList($model->getGroupsNames(), [
        'id'=>'group_id',
        'prompt'=>'Оберіть групу'
    ]);

    // Child # 1
    echo $form->field($model, 'subjects_id')->label('Оберіть предмет')->widget(DepDrop::classname(), [
        'options'=>['id'=>'subjects_id'],
        'pluginOptions'=>[
            'depends'=>['group_id'],
            'placeholder'=>'Оберіть предмет',
            'url'=>Url::to(['/timetable/subcatsubjects'])
        ]
    ]);

    echo $form->field($model, 'teacher_id')->label('Оберіть викладача')->widget(DepDrop::classname(), [
        'options'=>['id'=>'teacher_id'],
        'pluginOptions'=>[
            'depends'=>['subjects_id'],
            'placeholder'=>'Оберіть викладача',
            'url'=>Url::to(['/timetable/nakazteachers'])
        ]
    ]);

    ?>

    <?php
    /*
     * [['corps_id', 'audience_id', 'subjects_id', 'teacher_id', 'group_id', 'lecture_id', 'status','part_id', 'x', 'y', 'date'], 'required']
     * corps_id - передаём из формы
     * audience_id - передаём из формы
     * subjects_id - передаём из формы
     * teacher_id - берём из базы через id предмета после отправки формы
     * group_id - передаём из формы
     * lecture_id - берём из базы через номер пары $y после отправки формы
     * status - ставим 1
     * part_id - получаем из GET tp
     * x - получаем из GET x
     * y - получаем из GET y
     * date - получаем из GET date
    */

    $status = 1;
    $partId = $request->get('tp');
    $x = $request->get('x');
    $y = $request->get('y');
    $date = $request->get('date');
    ?>

	<? echo $form->field($model, 'status')->hiddenInput(['value' => '1'])->label(false); ?>

	<? echo $form->field($model, 'part_id')->hiddenInput(['value' => $partId])->label(false); ?>

	<? echo $form->field($model, 'x')->hiddenInput(['value' => $x])->label(false); ?>

	<? echo $form->field($model, 'y')->hiddenInput(['value' => $y])->label(false); ?>

	<? echo $form->field($model, 'date')->hiddenInput(['value' => $date])->label(false); ?>

    <?php

    echo $form->field($model, 'half')->label('Тривалість заняття (академ. год.)')->dropDownList($model->getLength(), [
        'id'=>'half',
        'prompt'=>'Оберіть тривалість'
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Створити', ['class' => 'btn btn-success']) ?>
    </div>

    <?php
    ActiveForm::end();
    ?>

</div>
