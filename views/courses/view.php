<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use \app\models\Lessons;
use yii\widgets\Pjax;
use yii\data\ActiveDataProvider;
use \app\models\Subjects;
use \app\models\Practice;
/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $this yii\web\View */
/* @var $searchModel app\models\AudienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courses-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <hr />
    <?php

    echo $this->render('_form', [
        'model' => $model,
        'modelLessons' => $modelLessons,
        'subjects' => $subjects,
        'status' => $status,
        'test' => $test
        //'id' => $model->ID,
    ])

    ?>
    <hr />

    <?php

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'summary' => "<div class=\"summary\">Показано {begin} - {end} з {totalCount} предметів</div>",
        'columns' => [
            //'ID',

            [
                'attribute' => 'subject_id',
                'format' => 'text',
                'label' => 'Предмет',
                'content' => function ($model, $key, $index, $column){
                    $subject = Subjects::find()->asArray()->where(['ID' => $model->subject_id])->one();
                    return $subject['name'];// . ' (' .$model->subject_id. ')';
                },
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-6'];
                }
            ],
            [
                'attribute' => 'quantity',
                'format' => 'text',
                'label' => 'Кількість занять',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-5'];
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'lessons',
                'template' => '{update} {delete}',

                'buttons' => [

                    'update' => function ($url, $model, $key) {
                        return Html::a(
                            "<span class=\"glyphicon glyphicon-pencil left\"></span>",
                            $url,

                            [
                                //'title' => v($model),
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
    ?>

    <hr />

    <p>
        <?= Html::a('Оновити', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Видалити', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете видалити цю професію?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>