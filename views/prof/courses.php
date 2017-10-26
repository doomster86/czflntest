<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Всі професії';
$this->params['breadcrumbs'][] = $this->title;
?>

<table class="table table-striped table-bordered col-sm-12">
    <thead>
        <tr>
            <th class="col-md-2">Назва курсу</th>
            <th class="col-md-1">Кількість занять виробничої практики</th>
            <th class="col-md-1">Кількість занять виробничого навчання</th>
            <th class="col-md-1">Кількість занять теоритичного навчання</th>
            <th class="col-md-5">Предмети</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($courses as $cours): ?>
        <tr>
            <td><?= Html::encode("{$cours->name}") ?></td>
            <td><?= Html::encode("{$cours->pract}") ?></td>
            <td><?= Html::encode("{$cours->worklect}") ?></td>
            <td><?= Html::encode("{$cours->teorlect}") ?></td>
            <td><?= Html::encode("{$cours->subject}") ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= LinkPager::widget(['pagination' => $pagination]) ?>
