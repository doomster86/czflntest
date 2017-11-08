<?php

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\Courses;
use app\models\Subjects;
use app\models\Lessons;
use yii\helpers\Html;
use app\models\CoursesSearch;

class CoursesController extends Controller {

    /**
     * Validate CoursesForm
     */

    public function actionIndex() {
        if(Yii::$app->user->identity->role==1) {
            $searchModel = new CoursesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->pagination = ['pageSize' => 15];

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->render('/site/access_denied');
        }
    }

    public function actionCreate() {

        if(Yii::$app->user->identity->role==1) {
            $model = new Courses();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // данные в $model удачно проверены

                $coursesName = Html::encode($model->name);

                $model->name = $coursesName;

                $model->save();

                //return $this->redirect(['courses-create', 'id' => $model->ID]);
                return $this->render('create', [
                    'model' => $model,
                    'operation' => 'created',

                ]);
            } else {
                // либо страница отображается первый раз, либо есть ошибка в данных
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('/site/access_denied');
        }

    }



    /**
     * Finds the Courses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {

        if(Yii::$app->user->identity->role==1) {
            if (($model = Courses::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('Сторінку не знайдено.');
            }
        } else {
            return $this->render('/site/access_denied');
        }

    }



    public function actionView($id) {

        if(Yii::$app->user->identity->role==1) {

            $model = $this->findModel($id);

            $subjects_add = array(0 => 'Оберіть предмет');

            $subjects_values = Subjects::find()->asArray()->select('name')->orderBy('ID')->all();
            $subjects_values = ArrayHelper::getColumn($subjects_values, 'name');

            $subjects_ids = Subjects::find()->asArray()->select('ID')->orderBy('ID')->all();
            $subjects_ids = ArrayHelper::getColumn($subjects_ids, 'ID');

            $subjects = array_combine($subjects_ids, $subjects_values);
            $subjects = ArrayHelper::merge($subjects_add, $subjects);


            $modelLessons = new Lessons();

            if ($modelLessons->load(Yii::$app->request->post()) && $modelLessons->validate()) { //если нажата зеленая кнопка

               // написать список переменных из формы _form.php

                $modelLessons->course_id = $id;
                $modelLessons->subject = Html::encode($modelLessons->subject);
                $modelLessons->quantity = Html::encode($modelLessons->quantity);

                $modelLessons->save();

                return $this->render('view', [
                    'model' => $model,
                    'modelLessons' => $modelLessons,
                    'subjects' => $subjects,



                ]);
            } else {
                return $this->render('view', [
                    'model' => $model,
                    'modelLessons' => $modelLessons,
                    'subjects' => $subjects,
                ]);
            }
            /*
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                return $this->render('view', [
                    'model' => $this->findModel($id),
                    //'subjects' => $subjects,
                ]);
            } else {

            }
            */
        } else {
            return $this->render('/site/access_denied');
        }

    }


    /**
     * Deletes an existing Courses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {

        if(Yii::$app->user->identity->role==1) {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        } else {
            return $this->render('/site/access_denied');
        }

    }


    /**
     * Updates an existing Courses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {

        if(Yii::$app->user->identity->role==1) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $coursesName = Html::encode($model->name);
                $model->name = $coursesName;
                $model->update();

                //return $this->redirect(['update', 'id' => $model->ID, 'operation' => 'updated']);
                return $this->render('update', [
                    'model' => $model,
                    'operation' => 'updated',
                ]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('/site/access_denied');
        }

    }

}