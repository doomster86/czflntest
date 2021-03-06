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
<?php
    $this->beginBody()
?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Центр зайнятості: Розклад',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Головна', 'url' => ['/site/index']],
            ['label' => 'Про центр', 'url' => ['/site/about']],
            ['label' => 'Розклад', 'url' => ['/timetable/index']],
            ['label' => 'Контакти', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (['label' => 'Вхід', 'url' => ['/subjects/index'], 'items' => [
                ['label' => 'Увійти', 'url' => ['/site/login']],
                ['label' => 'Зареєеструватись', 'url' => ['/site/signup']],
            ]]) : ['label' => Yii::$app->user->identity->username, 'items' => [
                [
                    'label' => 'Вийти',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post'],
                ],
                [
                    'label' => 'Змінити пароль',
                    'url' => ['/user/pass'],
                ],
            ]]
        ],
    ]);

    NavBar::end();
    ?>

    <?php
if(!Yii::$app->user->isGuest) {
    if(Yii::$app->user->identity->role==1) {
        NavBar::begin([
            'brandLabel' => 'Адміністрування',
            'brandUrl' => null,
            'options' => [
                'class' => 'navbar grey-menu',
            ],
        ]);

        $menuItems = [
            ['label' => 'Користувачі', 'url' => ['/user/index'],	'items' => [
                ['label' => 'Всі користувачі', 'url' => ['/user/index']],
				['label' => 'Викладачі: Звання', 'url' => ['/rank/index']],
	            ['label' => 'Викладачі: Ступінь', 'url' => ['/degree/index']],
	            ['label' => 'Викладачі: Категорія', 'url' => ['/skill/index']],
	            ['label' => 'Додати користувача', 'url' => ['/user/create']],
            ]],
            ['label' => 'Групи', 'url' => ['/groups/index'], 'items' => [
	            ['label' => 'Всі групи', 'url' => ['/groups/index']],
	            ['label' => 'Створити нову', 'url' => ['/groups/create']],
            ]],
            ['label' => 'Професії', 'url' => ['/courses/index'], 'items' => [
                ['label' => 'Всі професії', 'url' => ['/courses/index']],
                ['label' => 'Створити нову', 'url' => ['/courses/create']],
            ]],
            ['label' => 'Предмети', 'url' => ['/subjects/index'], 'items' => [
                ['label' => 'Всі предмети', 'url' => ['/subjects/index']]
            ]],
            ['label' => 'Корпуси', 'url' => ['/corps/index'], 'items' => [
                ['label' => 'Всі корпуси', 'url' => ['/corps/index']],
                ['label' => 'Cтворити новий', 'url' => ['/corps/create']],
                ['label' => 'Пари', 'url' => ['/lecture-table/index']]
            ]],
            ['label' => 'Аудиторії', 'url' => ['/audience/index'], 'items' => [
                ['label' => 'Всі аудиторії', 'url' => ['/audience/index']],
                ['label' => 'Cтворити нову', 'url' => ['/audience/create']],
            ]],
            ['label' => 'Управління розкладом', 'url' => ['#'], 'items' => [
                ['label' => 'Розклад', 'url' => ['/timetable-parts/index']],
                ['label' => 'Cтворити новый', 'url' => ['/timetable-parts/create']],
                ['label' => 'Фактичне педнавантаження', 'url' => ['/timetable-parts/godyny']],
                ['label' => 'Роздрукувати розклад', 'url' => ['/timetable-parts/printpage']],
            ]],
        ];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'activateParents'=>true,
            'items' => $menuItems,
        ]);
        NavBar::end();
    }

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

<footer class="footer hidden-print">
    <div class="container">
        <div class="col-lg-4">
            <h4>Телефони</h4>
            <p>(057) 786-71-60, 786-71-64 (м. Дергачі)<br/>
                (057) 738-12-89, 738-21-15 (м. Харків)</p>
        </div>
        <div class="col-lg-4">
            <h4>Адреса</h4>
            <p>62300 Харківська обл., Дергачівський район, м.Дергачі, вул.Наукова, 1.<br/>
                м. Харків, пров. Халтуріна, 3.</p>
        </div>
        <div class="col-lg-4">
            <h4>Графік работи</h4>
            <p>Пн-Пт з 8:30 до 17:00</p>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
