<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \app\models\Subjects;
use yii\helpers\ArrayHelper;
$this->title = 'Всі професії';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="courses-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Створити нову', ['courses-create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'summary' => "<div class='summary'>Показано {begin} - {end} з {totalCount} професій</div>",
        'tableOptions' => [
            'class' => 'table table-striped table-bordered col-xs-12 courses-table'
        ],
        'columns' => [

            [
                'attribute'=>'name',
                'label'=>'Професія',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-3'];
                }
            ],
            [
                'attribute'=>'subject',
                'label'=>'Предмети',
                'content' => function ($model, $key, $index, $column){
                    //$corps_name = Subjects::find()->asArray()->where(['ID' => $model->corps])->one();
                    //return $corps_name['name'];
                    $subject_id_arr = explode(", ", $model->subject);
                    $subject_name_arr = array();
                    foreach ($subject_id_arr as $subject_id) {
                        $subject_name_arr[] = Subjects::find()->asArray()->where(['ID' => $subject_id])->one();
                    }

                    $subject_name_arr = ArrayHelper::getColumn($subject_name_arr, 'name');
                    if (empty($subject_name_arr)) {
                        $subject_names = 'предмети не вказані';
                    } else {
                        $subject_names = implode(", ", $subject_name_arr);

                    }
                    return $subject_names;
                    //return print_r($subject_name_arr);
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-5'];
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