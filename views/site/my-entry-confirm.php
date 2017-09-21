<?php
use yii\helpers\Html;
?>
<p>Вы ввели следующую информацию:</p>

<ul>
    <li><label>Name</label>: <?= Html::encode($mymodel->name) ?></li>
    <li><label>Email</label>: <?= Html::encode($mymodel->email) ?></li>
</ul>