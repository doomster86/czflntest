<?php
use yii\helpers\Html;
?>
<p>Ви ввели наступну інформацію:</p>

<ul>
    <li><label>Ім`я</label>: <?= Html::encode($model->name) ?></li>
    <li><label>Email</label>: <?= Html::encode($model->email) ?></li>
</ul>