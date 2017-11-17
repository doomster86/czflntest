<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="user-item">
    <h2><?= Html::encode($model->firstname) ?> <?= Html::encode($model->lastname) ?></h2>

    <?php
    switch ($model->role){
        case 1:
            $img = "/img/admin.png";
            $role = "Адміністратор";
            break;
        case 2:
            $img = "/img/lector.png";
            $role = "Викладач";
            break;
        default:
            $img = "/img/student.png";
            $role = "Студент";
            break;
    }

    switch ($model->status) {
        case 0:
            $roleType = 'Заблокований';
            $roleClass = 'blocked';
            break;
        default:
            $roleType = 'Активний';
            $roleClass = 'active';
            break;
    }
    ?>
    <div class="img-holder">
        <?= Html::a(Html::img(Url::to('@web/').$img), Url::to('update/'.$model->id)); ?>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th colspan="2"><h3><?= Html::encode($role) ?></h3></th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ім'я</td>
                <td><?= Html::encode($model->firstname) ?></td>
            </tr>
            <tr>
                <td>По батькові</td>
                <td><?= Html::encode($model->middlename) ?></td>
            </tr>
            <tr>
                <td>Прізвище</td>
                <td><?= Html::encode($model->lastname) ?></td>
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
        </tbody>
    </table>
    <p class="center <?= $roleClass ?>"><?= $roleType ?></p>
</div>