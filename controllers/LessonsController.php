<?php
namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\Courses;
use app\models\CoursesSearch;
use app\models\Subjects;
use yii\helpers\Html;
use app\models\Lessons;
use app\models\LessonsSearch;

class LessonsController extends Controller{

    protected function findModel($id) {

        if(Yii::$app->user->identity->role==1) {
            if (($model = Lessons::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('Сторінку не знайдено.');
            }
        } else {
            return $this->render('/site/access_denied');
        }

    }

    public function actionUpdate($id) {
        if(Yii::$app->user->identity->role==1) {

            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                /*
                $subject = Html::encode($model->subject_id);
                $model->name = $subject;

                $quantity = Html::encode($model->quantity);
                $model->name = $quantity;
                */

                $model->update();

                return $this->redirect('/courses/'.$model->course_id);

            } else {

                $quantity = Html::encode($model->quantity);
                return $this->render('update', [
                    'model' => $model,
                    'quantity' => $quantity,
                    'status' => 'update'
                ]);
            }

        } else {
            return $this->render('/site/access_denied');
        }

    }

    public function actionDelete($id) {

        if(Yii::$app->user->identity->role==1) {
            $model = $this->findModel($id);
            $this->findModel($id)->delete();
            return $this->redirect(['/courses/'.$model->course_id]);
        } else {
            return $this->render('/site/access_denied');
        }

    }

}