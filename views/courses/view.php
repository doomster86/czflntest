<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use \app\models\Lessons;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model app\models\Courses */

Pjax::begin([
    // Pjax options
]);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courses-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <h4>_form.php</h4>
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
    <h4>table</h4>
    <hr />


    <?php
    /*
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'columns' => [
            [
                'attribute' => 'course_id',
                'format' => 'text',
                'label' => 'Назва',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-5'];
                }
            ],
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
    ]);

    //хз что
    /*
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'ID',
            'name',
        ],
    ]);
*/
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

<?php Pjax::end(); ?>