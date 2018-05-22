<?php

use yii\helpers\Html;
use app\models\Corps;

$this->title = 'Видалити корпус';
$this->params['breadcrumbs'][] = ['label' => 'Корпуси', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$audience = Corps::getAudience($id);
$lecture = Corps::getLecture($id);

?>

<h3><?php echo $model->corps_name; ?></h3>
<div clas="groups-list">
    <div class="alert alert-info">
        <?php if($audience != NULL): ?>
        <p>Ви не можете видалити корпус, доки він закріплений за аудиторіями:</p>
        <ul>
            <?php
            foreach ($audience as $audienc) {
                echo "<li>".Html::a($audienc['name'], ['/audience/update/', 'id' => $audienc['ID']], ['class' => 'link'])."</li>";
            }
            ?>
        </ul>
        <p>---</p>
        <?php endif; ?>
        <?php if($lecture != NULL): ?>
            <p>Ви не можете видалити корпус, доки він закріплений за парами:</p>
            <ul>
                <?php
                foreach ($lecture as $lectur) {
                    echo "<li>".Html::a($lectur['time_start'].' - '.$lectur['time_stop'], ['/lecture-table/update?id='.$lectur['ID']], ['class' => 'link'])."</li>";
                }
                ?>
            </ul>
        <?php endif; ?>
    </div>
</div>