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

    <h4>_form.php</h4>
    <hr />
    <?php

    echo $this->render('_form', [
        'model' => $model,
        'modelLessons' => $modelLessons,
        'subjects' => $subjects
        //'id' => $model->ID,
    ])

    ?>
    <hr />
    <h4>table</h4>
    <hr />
    <?php

    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'ID',
            'name',
        ],
    ]);

    ?>

    <hr />

    <p>
        <?= Html::a('Оновити', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Видалити', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете видалити цю професію?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>