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
<div class="teachers-time">
    <p>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Переглянути запланований час викладачів на поточний місяц
        </button>
    </p>
    <div class="collapse" id="collapseExample">
        <div class="card card-body">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Викладач</th>
                    <th>Тип викладача</th>
                    <th>Відпрацьовані години</th>
                    <th>Вільні години</th>
                    <th>Заплановані години</th>
                    <th>Годин на місяць</th>
                </tr>
                <tr>
                    <td><?php echo $model->getTeacherName(5) ?></td>
                    <td><?php echo $model->getTeacherType(5) ?></td>
                    <?php
                        $masHours = $model->getTeacherTime(5);
                    ?>
                    <td><?php echo $masHours['complete']; ?></td>
                    <td><?php echo $masHours['free']; ?></td>
                    <td><?php echo $masHours['month']; ?></td>
                    <td><?php echo $masHours['month']; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

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
