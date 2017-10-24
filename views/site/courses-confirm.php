<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<p>Новый курс создан!</p>

<ul>
    <li><label>Назва курсу: </label>: <?= Html::encode($model->name) ?></li>
    <li><label>Група курсу</label>: <?= Html::encode($model->group) ?></li>
    <li><label>Кількість занять виробничої практики</label>: <?= Html::encode($model->pract) ?></li>
    <li><label>Кількість занять виробничого навчання</label>: <?= Html::encode($model->worklect) ?></li>
    <li><label>Кількість занять теоритичного навчання</label>: <?= Html::encode($model->teorlect) ?></li>
</ul>

<?php
$url = Url::home();
?>
<ul>
    <li><a href="<?php echo $url; ?>site/courses">Створити нову професію</a></li>
    <li><a href="<?php echo $url; ?>">На головну</a></li>
</ul>