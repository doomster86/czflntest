<?php

use yii\helpers\Html;
use app\models\Audience;

$this->title = 'Видалити аудиторію';
$this->params['breadcrumbs'][] = ['label' => 'Аудиторії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$subjects = Audience::getSubjects($id);

?>

<h3><?php echo $model->name; ?></h3>
<div clas="groups-list">
    <div class="alert alert-info">
        <p>Ви не можете видалити аудиторію, доки вона закріплена за предметами:</p>
        <ul>
            <?php
            foreach ($subjects as $subject) {
                echo "<li>".Html::a($subject['name'], ['/subjects/update/', 'id' => $subject['ID']], ['class' => 'link'])."</li>";
            }
            ?>
        </ul>
    </div>
</div>