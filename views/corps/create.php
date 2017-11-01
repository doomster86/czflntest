<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Corps */

$this->title = 'Create Corps';
$this->params['breadcrumbs'][] = ['label' => 'Corps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="corps-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
