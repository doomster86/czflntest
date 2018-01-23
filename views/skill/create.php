<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Skill */

$this->title = 'Додати категорію';
$this->params['breadcrumbs'][] = ['label' => 'Кваліфікація', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skill-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('form', [
        'model' => $model,
        'status' => $status,
        'current_action' => 'create',
    ]) ?>

</div>
