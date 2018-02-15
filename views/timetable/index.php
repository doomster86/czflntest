<?php

use yii\helpers\Html;
use app\models\TimetableParts;
use yii\widgets\ActiveForm;

$this->title = 'Розклад';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="timetable-parts-view">

    <?php
    $form = ActiveForm::begin([
            'id' => 'timetable-selector',
            'action' => ['/timetable/index/'],
            'method' => 'get',
    ]);

    $items = $model->getTeachersNames();
    $params = [
	    'prompt' => 'Оберіть викладача'
    ];
    echo $form->field($TTViewer, 'teacher_id')->label(false)->dropDownList($items,$params);

    echo "<p>або</p>";

    $items = $model->getGroupsNames();
    $params = [
	    'prompt' => 'Оберіть групу'
    ];
    echo $form->field($TTViewer, 'group_id')->label(false)->dropDownList($items,$params);

    echo Html::submitButton('Відобразити', ['class' => 'btn btn-success']);

    ActiveForm::end();
    ?>

	<?php
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

    if($tableID != 0) {
	    $request = Yii::$app->request;
	    $request = $request->get();
	    if($request){
            $teacher_id = $request['TimetableViewer']['teacher_id'];
            $group_id = $request['TimetableViewer']['group_id'];

            if($teacher_id) {
                echo "<h2>Викладач: ".$model->getTeacherName($teacher_id)."</h2>";
            }
            if($group_id) {
                echo "<h2>Група: ".$model->getGroupName($group_id)."</h2>";
            }

            echo $model->renderTable($tableID, $teacher_id, $group_id);
        } else {
            echo $model->renderTable($tableID, '', '');
        }
    } else {
        echo "<p>Інформація відсутня</p>";
    }

	?>
</div>