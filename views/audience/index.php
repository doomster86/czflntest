<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AudienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Всі аудиторії';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audience-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p><?= Html::a('Додати аудиторію', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?php Pjax::begin(); ?>

    <?php var_dump($model_corps);

    $column = '444'?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'summary' => "<div class=\"summary\">Показано {begin} - {end} з {totalCount} аудиторій</div>",
        'columns' => [

            //'name',

            [
                'attribute' => 'name',
                'format' => 'text',
                'label' => 'Назва',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-5'];
                }
            ],

            [
                'attribute' => 'num',
                'format' => 'text',
                'label' => 'Номер',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-2'];
                }
            ],

            //select
            [
                'attribute' => 'corps',
                'format' => 'text',
                'label' => 'Корпус',
                'content' => function ($model, $key, $index, $column){
                    return 'ttt';
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                    //return '<span class=\"glyphicon glyphicon-pencil left\">qqq</span>"';
                    return [
                            'class' => 'col-xs-4',
                    ];
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
                                'alt' => 'Оновити',
                                'data-pjax' => '0',
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
                                'alt' => 'Видалити',
                                'data-pjax' => '0',
                            ]
                        );

                    },
                ],
                'contentOptions' => function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                }

            ],
        ],
    ]);

    Pjax::end(); ?>

</div>