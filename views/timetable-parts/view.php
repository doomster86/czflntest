<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\LectureTable;

/* @var $this yii\web\View */
/* @var $model app\models\TimetableParts */

$this->title = 'Розклад';
$this->params['breadcrumbs'][] = ['label' => 'Розклади', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timetable-parts-view">

    <?php
    $tableID = $model->id;
    if($tableID != 0) {
        echo \app\models\Timetable::renderTable($tableID, 0, 0);
    } else {
        echo "<p>Інформація відсутня</p>";
    }
    ?>

</div>
