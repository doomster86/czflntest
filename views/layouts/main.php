<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Центр зайнятості: Розклад',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);

    $menuItems = [
        ['label' => 'Головна', 'url' => ['/site/index']],
        ['label' => 'Про центр', 'url' => ['/site/about']],
        ['label' => 'Розклад', 'url' => ['#'], /*'visible' => !Yii::$app->user->isGuest*/],
        ['label' => 'Графік відвідування', 'url' => ['#'], /*'visible' => Yii::$app->user->identity->role=='admin'*/],
        ['label' => 'Контакти', 'url' => ['/site/contact']],
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вхід', 'url' => ['/subjects/index'], 'items' => [
            ['label' => 'Увійти', 'url' => ['/site/login']],
            ['label' => 'Зареєеструватись', 'url' => ['/site/signup']],
        ]];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Вийти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'activateParents'=>true,
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <?php
    if(Yii::$app->user->identity->role==1) {
        NavBar::begin([
            'brandLabel' => 'Адміністрування',
            'brandUrl' => null,
            'options' => [
                'class' => 'navbar grey-menu',
            ],
        ]);

        $menuItems = [
            ['label' => 'Користувачі', 'url' => ['/user/index']],
            ['label' => 'Групи', 'url' => ['#']],
            ['label' => 'Професії', 'url' => ['/courses/index'], 'items' => [
                ['label' => 'Всі професії', 'url' => ['/courses/index']],
                ['label' => 'Створити нову', 'url' => ['/courses/create']],
            ]],
            ['label' => 'Предмети', 'url' => ['/subjects/index'], 'items' => [
                ['label' => 'Всі предмети', 'url' => ['/subjects/index']],
                ['label' => 'Створити новий', 'url' => ['/subjects/create']],
            ]],
            ['label' => 'Корпуси', 'url' => ['/corps/index'], 'items' => [
                ['label' => 'Всі корпуси', 'url' => ['/corps/index']],
                ['label' => 'Cтворити новий', 'url' => ['/corps/create']],
            ]],
            ['label' => 'Аудиторії', 'url' => ['/audience/index'], 'items' => [
                ['label' => 'Всі аудиторії', 'url' => ['/audience/index']],
                ['label' => 'Cтворити нову', 'url' => ['/audience/create']],
            ]],
            ['label' => 'Заняття', 'url' => ['#']],
            ['label' => 'Управління розкладом', 'url' => ['#']],
        ];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'activateParents'=>true,
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'Головна', 'url' => '/'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="contacts">
            <h4>Контакти</h4>

            <p><span>Телефон: </span>111-222-333</p>

            <p><span>Адреса: </span>м. Місто, вул. Вулиця, б.11, офіс 123</p>
        </div>
        <div class="working">
            <h4>Графік работи</h4>

            <p>Пн-Пт с 10:00 до 19:00</p>

            <p>13:00-14:00 - перерва</p>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
