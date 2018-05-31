<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Контакти';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <table>
        <tr>
            <td><strong>Адреса:</strong></td>
            <td>62300 Харківська обл., Дергачівський район, м. Дергачі, вул. Наукова, 1.<br/>
                м. Харків, пров. Халтуріна, 3.
            </td>
        </tr>
        <tr>
            <td><strong>Телефон:</strong></td>
            <td>(057) 786-71-60, 786-71-64 (м. Дергачі)<br/>
                (057) 738-12-89, 738-21-15 (м. Харків)
            </td>
        </tr>
        <tr>
            <td><strong>E-mail:</strong></td>
            <td>osvita.edu@khcz.gov.ua (м. Дергачі)<br/>
                osvita.metod@khcz.gov.ua (м. Харків)
            </td>
        </tr>
        <tr>
            <td><strong>Графік роботи:</strong></td>
            <td>Пн-Пт з 8:30 до 17:00</td>
        </tr>
    </table>
</div>
