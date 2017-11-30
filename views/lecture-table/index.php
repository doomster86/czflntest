<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LectureTableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пари';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lecture-table-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Додати пару', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
