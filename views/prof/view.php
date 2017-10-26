<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courses-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Оновити', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Видалити', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Ви дійсно бажаєте видалити цю професію?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'name',
            'subject:ntext',
            'pract',
            'worklect',
            'teorlect',
        ],
    ]) ?>

</div>
