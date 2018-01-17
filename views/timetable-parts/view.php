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
        echo \app\models\Timetable::renderTable($tableID);
    } else {
        echo "<p>Інформація відсутня</p>";
    }
    print_r (\app\models\TimetableParts::getLectureId(1, 2));
    ?>

</div>
