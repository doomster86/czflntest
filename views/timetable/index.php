<?php

use yii\helpers\Html;
use yii\grid\GridView;

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

<div class="generate-timetable">
	<?php

	function renderTable($rows, $cols) {
		echo '<table class="table table-striped table-bordered">';
		for ($tr = 0; $tr <= $rows; $tr++) {
			if (!$tr) {
				echo '<thead><tr>';
				for ($td = 0; $td <= count($cols); $td++) {
				    if (!$td) {
					    echo '<th>#</th>';
                    } else {
					    echo '<th>' . $cols[$td-1] . '</th>';
				    }
				}
				echo '</tr></thead>';
			} else {
				echo '<tr>';
				for ($td = 0; $td <= count($cols); $td++) {
					if (!$td) {
						echo '<td>' . $tr . '</td>';
					} else {
						echo '<td>' . 'tr = ' . $tr . '; td = ' . $td . '</td>';
					}
				}
				echo '</tr>';
			}
		}
		echo "</table>";
	}


	$cols_array = array (
		'col1', 'col2', 'col3', 'col4', 'col5', 'col6'
	);
	renderTable(10,$cols_array);

	?>
</div>