<?php

use yii\helpers\Html;
use app\models\Courses;

$this->title = 'Видалити професію';
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$groups = Courses::getGroups($id);

?>

<h3><?php echo $model->name; ?></h3>
<div clas="groups-list">
    <div class="alert alert-info">
        <p>Ви не можете видалити профессію, доки вона закріплена за групами:</p>
        <ul>
            <?php
            foreach ($groups as $group) {
                echo "<li>".Html::a($group['name'], ['/groups/update/', 'id' => $group['ID']], ['class' => 'link'])."</li>";
            }
            ?>
        </ul>
    </div>
</div>