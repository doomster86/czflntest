<?php

use yii\helpers\Html;
use app\models\Rank;

$this->title = 'Видалити професію';
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$teachers = Rank::getTeachers($id);
?>

<h3><?php echo $model->rank_name; ?></h3>
<div clas="groups-list">
    <div class="alert alert-info">
        <p>Ви не можете видалити звання, доки воно закріплене за викладачами:</p>
        <ul>
            <?php
            foreach ($teachers as $teacher) {
                if($teacher['role'] == 0) {
                    echo "<li>".Html::a($teacher['firstname'].' '
                            .$teacher['middlename'].' '
                            .$teacher['lastname'], ['/user/update/', 'id' => $teacher['user_id']], ['class' => 'link'])
                        ." - УВАГА: зараз цей користувач має роль слухача</li>";
                } else {
                    echo "<li>".Html::a($teacher['firstname'].' '
                            .$teacher['middlename'].' '
                            .$teacher['lastname'], ['/user/update/', 'id' => $teacher['user_id']], ['class' => 'link'])
                        ."</li>";
                }
            }
            ?>
        </ul>
    </div>
</div>