<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AudienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Аудиторії';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audience-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Додати', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
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
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-4'];
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
<?php Pjax::end(); ?></div>
