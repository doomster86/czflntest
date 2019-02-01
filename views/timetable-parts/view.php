<?php

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\TimetableParts;

/* @var $this yii\web\View */
/* @var $model app\models\TimetableParts */

$this->title = 'Розклад';
$this->params['breadcrumbs'][] = ['label' => 'Розклади', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timetable-parts-view">

    <?php
    $form = ActiveForm::begin([
        'id' => 'timetable-selector',
        'action' => ['/timetable-parts/view/'],
        'method' => 'get',
    ]);

    $items = $timetable->getTeachersNames();
    $params = [
        'prompt' => 'Оберіть викладача'
    ];
    echo $form->field($TTViewer, 'teacher_id')->label(false)->dropDownList($items,$params);

    echo "<p>або</p>";

    $items = $timetable->getGroupsNames();
    $params = [
        'prompt' => 'Оберіть групу'
    ];
    echo $form->field($TTViewer, 'group_id')->label(false)->dropDownList($items,$params);
    echo Html::hiddenInput('id', $model->id);
    ?>
    <div class="form-group">
        <?php echo Html::submitButton('Відобразити', ['class' => 'btn btn-success']); ?>
    </div>
    <?php
    ActiveForm::end();
    ?>

    <?php
    $request = Yii::$app->request;
    $request = $request->get();
    if (isset($request['TimetableViewer']['teacher_id']) || isset($request['TimetableViewer']['group_id'])) {
    $currentDate = strtotime('now');

    //$currentDate >= datestart
    //$currentDate <= dateend
    $tableID = new TimetableParts();

    $tableID = $tableID->find()
        ->asArray()
        ->select(['id'])
        ->where(['<=', 'datestart', $currentDate]) // datestart <= $currentDate
        ->andWhere(['>=', 'dateend', $currentDate])// dateend >= $currentDate
        ->one();
    $tableID = $tableID['id'];

    /*
    $tableID = $tableID->find()
        ->asArray()
        ->select(['mont'])
        ->where(['<=', 'datestart', $currentDate]) // datestart <= $currentDate
        ->andWhere(['>=', 'dateend', $currentDate])// dateend >= $currentDate
        ->one();
    $tableID = $tableID['mont'];
    */

    //echo $tableID;

    if($tableID != 0) {
        if(!empty($request['TimetableViewer'])){
            $teacher_id = $request['TimetableViewer']['teacher_id'];
            $group_id = $request['TimetableViewer']['group_id'];

            if($teacher_id) {
                echo "<h2>Викладач: ".$timetable->getTeacherName($teacher_id)."</h2>";
            }
            if($group_id) {
                echo "<h2>Група: ".$timetable->getGroupName($group_id)."</h2>";
            }

            echo $timetable->renderTable($tableID, $teacher_id, $group_id);
            //echo $model->renderTableForMont($tableID, $teacher_id, $group_id);
        } else {
            echo $timetable->renderTable($tableID, '', '');
            //$model->renderTableForMont($tableID, '', '');
        }
    } else {
        echo "<p>Інформація відсутня</p>";
    }
    }

    ?>
</div>
<?php
    if (!isset($request['TimetableViewer']['teacher_id']) && !isset($request['TimetableViewer']['group_id'])) { ?>
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
<?php } ?>
<div class="timetable-parts-form">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['addgroup'],
    ]); ?>

    <div class="form-group">
        <?php
        $request = Yii::$app->request;
        $id = $request->get('id');
        $fmodel = new \app\models\AddGroupToTable();
        $options = array(
            'options' =>  [
                0 => [
                    'disabled' => true,
                    'selected' => 'selected',
                    //'' => '',
                ],
                //1 => ...
            ]
        );
        echo $form->field($model, 'id')
            ->hiddenInput(['name'=>'id','value'=>$id])
            ->label(false, ['style'=>'display:none']);
        echo $form->field($fmodel, 'gid')
            ->dropDownList($model->getGroupNames(), ['name'=>'gid','prompt'=>'--- Оберіть групу ---'])
            ->label(false, ['style'=>'display:none']);
        ?>
        <?= Html::submitButton( 'Створити розклад для групи', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
