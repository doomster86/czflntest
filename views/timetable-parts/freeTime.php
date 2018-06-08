<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TimetableParts */

$this->title = 'Залишок годин по предмету';
$this->params['breadcrumbs'][] = ['label' => 'Розклади', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="subject_time">
    <h3><?php echo $this->title; ?></h3>
    <table class="table">
        <tr>
            <th>Група</th>
            <th>Предмет</th>
            <th>Викладач</th>
            <th>Заплановано годин</th>
            <th>Заплановано в розкладі</th>
            <th>Залишилось</th>
        </tr>
        <tr>
            <td><?php echo $group; ?></td>
            <td><?php echo $subject; ?></td>
            <td><?php echo $teacher; ?></td>
            <td><?php echo $lessons; ?></td>
            <td><?php echo $intable; ?></td>
            <td><?php echo $more; ?></td>
        </tr>
    </table>
</div>
