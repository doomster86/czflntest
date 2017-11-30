<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LectureTable */

$this->title = 'Create Lecture Table';
$this->params['breadcrumbs'][] = ['label' => 'Lecture Tables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lecture-table-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
