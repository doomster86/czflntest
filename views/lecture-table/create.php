<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LectureTable */

$this->title = 'Додати пару';
$this->params['breadcrumbs'][] = ['label' => 'Пари', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lecture-table-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
    ]) ?>

</div>
