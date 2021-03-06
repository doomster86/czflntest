<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Audience */

$this->title = 'Додати аудиторію';
$this->params['breadcrumbs'][] = ['label' => 'Аудиторії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="audience-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <hr />

    <?php

    echo $this->render('form', [
        'model' => $model,
        'current_action' => 'create',
        'status_form' => $status
    ])

    ?>

</div>
