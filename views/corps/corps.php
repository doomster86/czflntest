<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CorpsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Корпуси';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="corps-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Додати корпус', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        //'layout'=>"{items}",
        'summary' => "<div class=\"summary\">Показано {begin} - {end} з {totalCount} корпусів</div>",
        //'tableOptions' => ['class' => 'table table-striped table-bordered my-table'],
        //'caption' => 'MyCaption',
        //'dataColumnClass' => 'col-class',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'format' => 'text',
                'label' => 'Назва',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-5'];
                }
            ],

            [
                'attribute' => 'location',
                'format' => 'text',
                'label' => 'Розташування',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-6'];
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',

                'buttons' => [

                    'update' => function ($url, $model, $key) {
                        return Html::a(
                            "<span class=\"glyphicon glyphicon-pencil left\"></span>",
                                $url,
                                [
                                    'title' => 'Оновити',
                                    'aria-label' => 'Оновити',
                                    'alt' => 'Оновити'
                                ]
                        );
                    },

                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                                    "<span class=\"glyphicon glyphicon-trash right\"></span>",
                                    $url,
                                    [
                                        'title' => 'Видалити',
                                        'aria-label' => 'Видалити',
                                        'alt' => 'Видалити'
                                    ]
                                );

                    },
                ],
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                }

            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
