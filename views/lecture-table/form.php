<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\time\TimePicker;
use yii\grid\GridView;
//use app\models\Corps;
/* @var $this yii\web\View */
/* @var $model app\models\LectureTable */
/* @var $form yii\widgets\ActiveForm */

Pjax::begin([
// Pjax options
]);

?>

<div class="lecture-table-form">
    <?php
    $time1 = '0:05';
    $startsec = strtotime('01/01/1970'.$time1.' gmt').'<br/>';
    $stopsec = strtotime('01/01/1970 '.$time1.' gmt').'<br/>';
    //echo $startsec;
    ?>
    <?php if (Html::encode($status_form) == 'created') : ?>
        <div class="alert alert-success">
            <p>Додано нову пару. </p>
            <p>Час початку: <strong><?= Html::encode($model->time_start) ?></strong></p>
            <p>Час закінчення: <strong><?= Html::encode($model->time_stop) ?></strong></p>
            <p>Корпус: <strong><?= Html::encode($model->corpsName) ?></strong></p>
        </div>
    <?php endif; ?>

    <?php if (Html::encode($status_form) == 'updated') : ?>
        <div class="alert alert-success">
            <p>Пару оновлено.</p>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'options' => [
            'data' => [
                'pjax' => true,
            ],
            'id' => 'lecture-table-form'
        ],
    ]);

    $startValue = 'current';
    $stopValue = 'current';
    if (Html::encode($current_action) == 'update') {
        $startValue = $model->time_start;
        $stopValue = $model->time_stop;
    }

    echo $form->field($model, 'time_start')
        ->label('Час початку')
        ->widget(TimePicker::className(),[
            'pluginOptions' => [
                'showSeconds' => false,
                'showMeridian' => false,
                'minuteStep' => 1,
                'defaultTime' => $startValue
            ]
        ]);

    ?>

	<?php //echo $form->field($model, 'time_start')->label('Час початку')->textInput();?>
	<?php //echo $form->field($model, 'time_stop')->label('Час закінчення')->textInput();?>
    <?php

    echo $form->field($model, 'time_stop')->label('Час закінчення')
        ->label('Час початку')
        ->widget(TimePicker::className(),[
        'pluginOptions' => [
		    'showSeconds' => false,
		    'showMeridian' => false,
		    'minuteStep' => 1,
            'defaultTime' => $stopValue
	    ]
    ]);

    ?>

    <?= $form->field($model, 'corps_id')->label('Корпус')->dropDownList($model->getCorpsNames()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Додати' : 'Оновити', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => 'Нічого не знайдено',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'summary' => "<div class='summary'>Показано {begin} - {end} з {totalCount} пар</div>",
        'tableOptions' => [
            'class' => 'table table-striped table-bordered col-xs-12 lecture-table'
        ],
        'columns' => [
            [
                'attribute' =>  'time_start',
                'label' => 'Час початку',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
            ],
            [
                'attribute' =>  'time_stop',
                'label' => 'Час закінчення',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
            ],
            [
                'attribute' =>  'corpsName',
                'label' => 'Корпус',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-4'];
                }
            ],



        ],
    ]); ?>


</div>
<?php Pjax::end(); ?>