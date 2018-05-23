<?php

use yii\helpers\Html;
use app\models\Skill;

$this->title = 'Видалити категорію';
$this->params['breadcrumbs'][] = ['label' => 'Категорії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$teachers = Skill::getTeachers($id);
?>

<h3><?php echo $model->skill_name; ?></h3>
<div clas="groups-list">
    <div class="alert alert-info">
        <p>Ви не можете видалити категорію, доки вона закріплена за викладачами:</p>
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