<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\Timetable;
use app\models\TimetableParts;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TimetableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Розклад';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="generate-timetable">
	<?php
    $currentDate = strtotime('now');

    $tableID = new TimetableParts();
    $tableID = $tableID->find()
        ->asArray()
        ->select(['id'])
        ->where(['<=', 'datestart', $currentDate]) // $currentDate <= datestart
        ->andWhere(['>=', 'dateend', $currentDate])// $currentDate >= dateend
        ->one();
    $tableID = $tableID['id'];

    if($tableID != 0) {
        echo $model->renderTable($tableID);
    } else {
        echo "<p>Інформація відсутня</p>";
    }
    //\app\models\TimetableParts::generateLectures('1514149200', '1514667600','7', '5');
	?>
</div>