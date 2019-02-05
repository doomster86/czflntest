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
            <th>Всього годин по РНП</th>
            <th>Всього в розкладі</th>
            <th>Всього в РНП на обраний тиждень</th>
            <th>У розкладі на обраний тиждень</th>
        </tr>
        <tr>
            <td><?php echo $group; ?></td>
            <td><?php echo $subject; ?></td>
            <td><?php echo $teacher; ?></td>
            <td><?php echo $allrnp; ?></td>
            <td><?php echo $allrozklad; ?></td>
            <td><?php echo $weekrnp; ?></td>
            <td><?php echo $weekrozklad; ?></td>
        </tr>
    </table>
</div>
