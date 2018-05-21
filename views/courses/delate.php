<?php

use yii\helpers\Html;

$this->title = 'Видалити професію';
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= Html::a('Видалити остаточно', ['cdelate', 'id' => $id], ['class' => 'btn btn-success']) ?>
<?= Html::a('Редагувати', ['update', 'id' => $id], ['class' => 'btn btn-success']) ?>
