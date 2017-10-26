<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Всі професії';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="courses-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Створити професію', ['courses-create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered col-xs-12 courses-table'
        ],
        'columns' => [

            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                }
            ],

            //'ID',
            [
                'attribute'=>'name',
                'label'=>'Професія',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-2'];
                }
            ],
            [
                'attribute'=>'subject',
                'label'=>'Предмети',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-5'];
                }
            ],
            [
                'attribute'=>'pract',
                'label'=>'Кількість занять виробничої практики',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                }
            ],
            [
                'attribute'=>'worklect',
                'label'=>'Кількість занять виробничого навчання',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                }
            ],
            [
                'attribute'=>'teorlect',
                'label'=>'Кількість занять теоритичного навчання',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>