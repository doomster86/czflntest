<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\Timetable;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TimetableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Розклад';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timetable-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Timetable', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'corps_id',
            'audience_id',
            'subjects_id',
            'teacher_id',
            'group_id',
            'lecture_id',
            'date:ntext',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
<hr />
<div class="generate-timetable">
	<?php
	echo $model->renderTable(27);
	?>
</div>