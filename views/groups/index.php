<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Courses;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\GroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
Pjax::begin();

$this->title = 'Групи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="groups-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php  ?>

    <p>

        <?= Html::a('Додати групу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'emptyText' => 'Нічого не знайдено',
	    'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
	    'summary' => "<div class='summary'>Показано {begin} - {end} з {totalCount} груп</div>",
	    'tableOptions' => [
		    'class' => 'table table-striped table-bordered col-xs-12 courses-table'
	    ],
        'columns' => [

	        [
		        'attribute'=>'name',
		        'label'=>'Назва/номер',
		        'contentOptions' =>function ($model, $key, $index, $column){
			        return ['class' => 'col-xs-3'];
		        }
	        ],

	        [
		        'attribute' => 'coursesName',
		        'label' => 'Професія',
		        'contentOptions' =>function ($model, $key, $index, $column){
			        return ['class' => 'col-xs-3'];
		        }
	        ],

            [
                'attribute' => 'userName',
                'label' => 'Куратор',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
            ],

            [
                'attribute' => 'date_start',
                'format' => 'text',
                'label' => 'Дата посадки',
                'content' => function ($model, $key, $index, $column){
                    $formatter = new \yii\i18n\Formatter;
                    return $formatter->asDate($model->date_start, 'dd.MM.yyyy');
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
            ],

            [
                'attribute' => 'date_end',
                'format' => 'text',
                'label' => 'Дата закінчення навчання',
                'content' => function ($model, $key, $index, $column){
                    $formatter = new \yii\i18n\Formatter;
                    return $formatter->asDate($model->date_end, 'dd.MM.yyyy');
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
	            'buttons' => [

		            'update' => function ($url,$model) {
			            return Html::a(
				            '<span class="glyphicon glyphicon-pencil"></span>',
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
<?php Pjax::end(); ?></div>
