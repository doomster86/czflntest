<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;


class TimetableController extends Controller {

    public function actionIndex() {
        if(Yii::$app->user->identity->role==1) {
            return $this->render('index', [
            ]);
        } else {
            return $this->render('/site/access_denied');
        }
    }

}