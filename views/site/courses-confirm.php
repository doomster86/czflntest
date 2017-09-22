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

<?php
$url = Url::home();
?>
<ul>
    <li><a href="<?php echo $url; ?>site/courses">Создать курс</a></li>
    <li><a href="<?php echo $url; ?>">На главную</a></li>
</ul>