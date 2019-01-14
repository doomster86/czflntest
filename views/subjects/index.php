<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
//use \app\models\Teachers;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Всі предмети';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subjects-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'summary' => "<div class='summary'>Показано {begin} - {end} з {totalCount} предметів</div>",
        'tableOptions' => [
            'class' => 'table table-striped table-bordered col-xs-12 subjects-table'
        ],
        'columns' => [

            //'ID',
            [
                'attribute'=>'title',
                'label'=>'Предмет',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-2'];
                }
            ],
            //'teacher_id',

            [
                'attribute' => 'teacherName',
                'label' => 'Викладач',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
          ],

//            [
//                'attribute' => 'audienceName',
 //               'label' => 'Аудиторія',
//                /*
//                'content' => function ($model, $key, $index, $column) {
 //                  return 'Номер '.$model->getAudienceName();
 //               },*/
 //               'contentOptions' =>function ($model, $key, $index, $column){
 //                   return ['class' => 'col-xs-3'];
 //               }
//            ],
            //'audience_id',
            //'required',
//            [
 //               'attribute'=>'max_week',
 //               'label'=>'Макс. занять на тиждень',
 //               'contentOptions' =>function ($model, $key, $index, $column){
//                    return ['class' => 'col-xs-2'];
//                }
//            ],


            /*




            [
                'attribute'=>'teacher_id',
                'label'=>'Викладач',
                'content' => function ($model, $key, $index, $column){
                    return $model->getTeacherName();
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
            ],

            [
                'attribute'=>'audience_id',
                'label'=>'Аудиторія',
                'content' => function ($model, $key, $index, $column){
                    return $model->getAudienceName();
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
            ],


*/

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [

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
                ],
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-1'];
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
