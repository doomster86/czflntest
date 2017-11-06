<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Всі предмети';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subjects-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Створити новий', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'summary' => "<div class='summary'>Показано {begin} - {end} з {totalCount} предметів</div>",
        'tableOptions' => [
            'class' => 'table table-striped table-bordered col-xs-12 courses-table'
        ],
        'columns' => [
            [
                'attribute'=>'name',
                'label'=>'Предмет',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-11'];
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{view} {update} {delete}',
                'template' => '{update} {delete}',
                'buttons' => [
                    /*
                'view' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open"></span>',
                        $url,
                        [
                            'title' => 'Переглянути',
                            'data-pjax' => '0',
                        ]
                        );
                },
                    */
                    'update' => function ($url,$model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil left"></span>',
                            $url,
                            [
                                'title' => 'Редагувати',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                    'delete' => function ($url,$model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash right"></span>',
                            $url,
                            [
                                'title' => 'Видалити',
                                'data-pjax' => '0',
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
