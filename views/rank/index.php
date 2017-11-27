<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RankSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ranks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rank-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Rank', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'rank_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
