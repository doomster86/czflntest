<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SkillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категорії';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skill-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Додати', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => 'Нічого не знайдено',
        'layout'=>"{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'summary' => "<div class=\"summary\">Показано {begin} - {end} з {totalCount} категорій</div>",
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'skill_name',
                'format' => 'text',
                'label' => 'Назва',
                'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'col-xs-11'];
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
        ],
    ]); ?>
</div>
