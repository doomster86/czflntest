<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Додати користувача';
$this->params['breadcrumbs'][] = ['label' => 'Всі користувачі', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    /*
    echo $this->render('form', [
        'model' => $model,
    ]);
    */
    if(Yii::$app->user->identity->role == 1 ) {
	    echo $this->render('addUser', [
		    'model' => $model,
            'status' => $status
	    ]);
    } else {
	    return $this->render('/site/access_denied');
    }
    ?>

</div>
