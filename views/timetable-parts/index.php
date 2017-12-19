<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TimetablePartsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Розклади';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="timetable-parts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Створити новий розклад', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'summary' => "<div class=\"summary\">Показано {begin} - {end} з {totalCount} розкладів</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'datestart',
                'format' => 'text',
                'label' => 'Дата початку розкладу',
                'content' => function ($model, $key, $index, $column){
                    $formatter = new \yii\i18n\Formatter;
                    return $formatter->asDate($model->datestart, 'dd.MM.yyyy');
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-5'];
                }
            ],

            [
                'attribute' => 'dateend',
                'format' => 'text',
                'label' => 'Дата кінця розкладу',
                'content' => function ($model, $key, $index, $column){
                    $formatter = new \yii\i18n\Formatter;
                    return $formatter->asDate($model->dateend, 'dd.MM.yyyy');
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-5'];
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

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
