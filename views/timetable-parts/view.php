<?php

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

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
                    <!--<th>Відпрацьовані години</th>-->
                    <th>Заплановані години</th>
                    <th>Вільні години</th>
                    <th>Годин на місяць</th>
                </tr>
                <?php
                $teacher_values = \app\models\User::find()->asArray()->select(['id'])
                    ->where(['role' => 2, 'status' => 1])
                    ->orderBy('id')
                    ->all();
                $teacher_ids = ArrayHelper::getColumn($teacher_values, 'id');
                foreach ($teacher_ids as $id) {
                    ?>
                    <tr>
                        <td><?php echo $model->getTeacherName($id) ?></td>
                        <td><?php echo $model->getTeacherType($id) ?></td>
                        <?php
                        $masHours = $model->getTeacherTime($id, $model->id);
                        ?>
                        <!--<td><?php //echo $masHours['work']; ?></td>-->
                        <td><?php echo $masHours['gen']; ?></td>
                        <td><?php echo $masHours['free']; ?></td>
                        <td><?php echo $masHours['month']; ?></td>
                    </tr>
                    <?php
                }
                ?>
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

<div class="timetable-parts-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?//= Html::submitButton( 'Створити розклад для групи', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
