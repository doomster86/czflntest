<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
//use \app\models\Teachers;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Вся виробнича практика';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practice-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <p><?= Html::a('Створити нову практику', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'summary' => "<div class='summary'>Показано {begin} - {end} з {totalCount} практики</div>",
        'tableOptions' => [
            'class' => 'table table-striped table-bordered col-xs-12 subjects-table'
        ],
        'columns' => [
            [
                'attribute'=>'name',
                'label'=>'Назва практики',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-2'];
                }
            ],

            [
                'attribute' => 'teacherName',
                'label' => 'Викладач',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
            ],

	        [
		        'attribute' => 'audienceName',
		        'label' => 'Аудиторія',
		        /*
				'content' => function ($model, $key, $index, $column) {
				   return 'Номер '.$model->getAudienceName();
				},*/
		        'contentOptions' =>function ($model, $key, $index, $column){
			        return ['class' => 'col-xs-3'];
		        }
	        ],

/*
            [
                'attribute'=>'master_id',
                'label'=>'Викладач',
                'content' => function ($model, $key, $index, $column){
                    return $model->getTeacherName();
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
            ],
*/
            [
                'attribute'=>'max_week',
                'label'=>'Макс. на тиждень',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-2'];
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
