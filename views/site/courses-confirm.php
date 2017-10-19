<?php
use yii\helpers\Html;
?>
<p>Новый курс создан!</p>

<ul>
    <li><label>Назва курсу: </label>: <?= Html::encode($model->name) ?></li>
    <li><label>Викладач курсу</label>: <?= Html::encode($model->teacher) ?></li>
    <li><label>Група курсу</label>: <?= Html::encode($model->group) ?></li>
    <li><label>Кількість лукцій курсу</label>: <?= Html::encode($model->lections) ?></li>
    <li><label>Кількість практичних занять курсу</label>: <?= Html::encode($model->practics) ?></li>
</ul>

<?php
$url = Url::home();
?>
<ul>
    <li><a href="<?php echo $url; ?>site/courses">Створити новий курс</a></li>
    <li><a href="<?php echo $url; ?>">На головну</a></li>
</ul>