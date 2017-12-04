<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Degree */

$this->title = 'Додати ступінь';
$this->params['breadcrumbs'][] = ['label' => 'Ступені', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="degree-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
