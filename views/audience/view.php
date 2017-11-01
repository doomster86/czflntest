<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Audience */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Аудиторії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audience-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Оновити', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Видалити', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете видалити цю аудиторію?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'ID',
            'name',
            'num',
            'corps',
        ],
    ]) ?>

</div>
