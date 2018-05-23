<?php

use yii\helpers\Html;
use app\models\Degree;

$this->title = 'Видалити ступінь';
$this->params['breadcrumbs'][] = ['label' => 'Ступені', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$teachers = Degree::getTeachers($id);
?>

<h3><?php echo $model->degree_name; ?></h3>
<div clas="groups-list">
    <div class="alert alert-info">
        <p>Ви не можете видалити ступінь, доки вона закріплена за викладачами:</p>
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