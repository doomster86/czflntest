<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use app\models\StudentMeta;
use app\models\TeacherMeta;
?>

<div class="user-item">
    <h2><?= Html::encode($model->firstname) ?> <?= Html::encode($model->lastname) ?></h2>

    <?php

    $roles = User::ROLES;
    $status = User::STATUS;

    ?>
    <div class="img-holder">
        <?= Html::a(Html::img(Url::to('@web/').$roles[$model->role]['img']), Url::to('update/'.$model->id)); ?>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th colspan="2"><h3><?= Html::encode($roles[$model->role]['name']) ?></h3></th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>Прізвище</td>
                <td><?= Html::encode($model->lastname) ?></td>
            </tr>
            <tr>
                <td>Ім'я</td>
                <td><?= Html::encode($model->firstname) ?></td>
            </tr>
            <tr>
                <td>По батькові</td>
                <td><?= Html::encode($model->middlename) ?></td>
            </tr>
            <tr>
                <td>Логін</td>
                <td><?= Html::encode($model->username) ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?= Html::encode($model->email) ?></td>
            </tr>
            <tr>
                <td>Телефон</td>
                <td><?= Html::encode($model->phone) ?></td>
            </tr>
            <?php
            // если студент
            if ($roles[$model->role]['roles'] == 0) : ?>
            <tr>
                <td>Група</td>
                <td><?= StudentMeta::getCurrentGroupName($model->ID) ?></td>
            </tr>
            <?php endif; ?>
            <?php
            // если препод
            if ($roles[$model->role]['roles'] == 2) : ?>
                <tr>
                    <td>Тип викладача</td>
                    <td><?= TeacherMeta::getTeacherType($model->ID); ?></td>
                </tr>
                <tr>
                    <td>Робочі дні</td>
                    <td><?php echo TeacherMeta::getTeacherWorkDays($model->ID); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p class="center">Зареєстровано <?php echo $model->getRegisterDate(); ?></p>
    <p class="center <?= Html::encode($status[$model->status]['cssClass']) ?>"><?= Html::encode($status[$model->status]['name']) ?></p>
</div>