<?php
use yii\helpers\Html;
?>
<p>Новый курс создан!</p>

<ul>
    <li><label>Название курса: </label>: <?= Html::encode($model->name) ?></li>
    <li><label>Преподаватель курса</label>: <?= Html::encode($model->teacher) ?></li>
    <li><label>Группа курса</label>: <?= Html::encode($model->group) ?></li>
    <li><label>Кол-во курса</label>: <?= Html::encode($model->lections) ?></li>
    <li><label>Кол-во практических занятий курса</label>: <?= Html::encode($model->practics) ?></li>
</ul>

<ul>
    <li><a href="/site/courses">Создать курс</a></li>
    <a href="/site/index">На главную</a>
</ul>