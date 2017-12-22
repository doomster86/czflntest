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

<div class="generate-timetable">
	<?php
	echo $model->renderTable(57);
    \app\models\TimetableParts::generate('1514149200', '1514667600');
	?>
</div>