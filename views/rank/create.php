<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Rank */

$this->title = 'Додати звання';
$this->params['breadcrumbs'][] = ['label' => 'Звання', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rank-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
