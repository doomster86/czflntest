<?php

use yii\helpers\Html;
use \app\models\Subjects;
use \app\models\Courses;
/* @var $this yii\web\View */
/* @var $model app\models\Audience */
$subject = Subjects::find()->asArray()->where(['ID' => $model->subject_id])->one();
$this->title = 'Оновити: ' . $subject['name'];
$course_id = Html::encode($model->course_id);
$course = Courses::find()->asArray()->where(['ID' => $model->course_id])->one();
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['/courses/']];
$this->params['breadcrumbs'][] = ['label' => $course['name'], 'url' => ['/courses/'.$course_id]];
$this->params['breadcrumbs'][] = 'Оновити';
?>
<div class="lessons-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr />

    <?= $this->render('form', [
        'model' => $model,
        'status' => $status,
        'quantity' => $quantity
    ]) ?>

</div>