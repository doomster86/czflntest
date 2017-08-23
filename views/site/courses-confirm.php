<?php
use yii\helpers\Html;
?>
<p>Новый курс создан!</p>

<ul>
    <li><label>Название курса: </label>: <?= Html::encode($model->name) ?></li>
    <li><label>Преподаватель курса</label>: <?= Html::encode($model->teacher) ?></li>
</ul>