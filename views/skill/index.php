<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SkillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Кваліфікації';
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
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'ID',
            'skill_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
