<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use \app\models\Lessons;
use yii\widgets\Pjax;
use yii\data\ActiveDataProvider;
use \app\models\Subjects;
use \app\models\Practice;
/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $this yii\web\View */
/* @var $searchModel app\models\AudienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Професії', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courses-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (empty($RnpsArray)) { ?>
        <hr/>
        <?php

        echo $this->render('_form');
    } else {
        echo $this->render('_view_rnp', [
            'UsersArray' => $UsersArray,
            'RnpsArray' => $RnpsArray,
            'RnpSubjectsArray' => $RnpSubjectsArray,
            'weeksArray' => $weeksArray,
            'modulesArray' => $modulesArray,
            'nakazArray' => $nakazArray,
            'teachersArray' => $teachersArray
        ]);
    }
    ?>

</div>