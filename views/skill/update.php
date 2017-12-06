<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Skill */

$this->title = 'Оновити кваліфікацію: ';
$this->params['breadcrumbs'][] = ['label' => 'Кваліфікація', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Оновити';
?>
<div class="skill-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
        'status' => $status,
        'current_action' => 'update',
    ]) ?>

</div>
