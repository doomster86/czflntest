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

use yii\web\NotFoundHttpException;

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
                    'status' => 'created'
                    //'operation' => 'created',

                ]);
            } else {
                // либо страница отображается первый раз, либо есть ошибка в данных
                return $this->render('create', [
                    'model' => $model,
                    'status' => '',
                    //'operation' => ''
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

            $searchModel = new LessonsSearch();
            $params = Yii::$app->request->queryParams;
            $params['course_id'] = $id;
            $dataProvider = $searchModel->search($params);
            $dataProvider->pagination = ['pageSize' => 15];

            $model = $this->findModel($id);

            $selected_subjects = Lessons::find()->asArray()->select('subject_id')->where(['course_id' => $id])->orderBy('subject_id')->all();
            $selected_subjects = ArrayHelper::getColumn($selected_subjects, 'subject_id');
            $selected_subjects = array_combine($selected_subjects, $selected_subjects);

            $subjects_values = Subjects::find()->asArray()->select('name')->orderBy('ID')->all();
            $subjects_values = ArrayHelper::getColumn($subjects_values, 'name');

            $subjects_ids = Subjects::find()->asArray()->select('ID')->orderBy('ID')->all();
            $subjects_ids = ArrayHelper::getColumn($subjects_ids, 'ID');

            $subjects = array_combine($subjects_ids, $subjects_values);

            $subjects_add = array(0 => 'Оберіть предмет');
            $subjects = ArrayHelper::merge($subjects_add, $subjects);

            $subjects = array_diff_key($subjects, $selected_subjects);

            $modelLessons = new Lessons();

            if ($modelLessons->load(Yii::$app->request->post()) && $modelLessons->validate()) { //если нажата зеленая кнопка

                $modelLessons->course_id = $id;
                $modelLessons->subject_id = Html::encode($modelLessons->subject_id); //select->option->value
                $modelLessons->quantity = Html::encode($modelLessons->quantity);

                $modelLessons->save(); //запись в таблицу

                $selected_subjects = Lessons::find()->asArray()->select('subject_id')->where(['course_id' => $id])->orderBy('subject_id')->all();
                $selected_subjects = ArrayHelper::getColumn($selected_subjects, 'subject_id');
                $selected_subjects = array_combine($selected_subjects, $selected_subjects);

                $subjects_values = Subjects::find()->asArray()->select('name')->orderBy('ID')->all();
                $subjects_values = ArrayHelper::getColumn($subjects_values, 'name');

                $subjects_ids = Subjects::find()->asArray()->select('ID')->orderBy('ID')->all();
                $subjects_ids = ArrayHelper::getColumn($subjects_ids, 'ID');

                $subjects = array_combine($subjects_ids, $subjects_values);

                $subjects_add = array(0 => 'Оберіть предмет');
                $subjects = ArrayHelper::merge($subjects_add, $subjects);

                $subjects = array_diff_key($subjects, $selected_subjects);

                return $this->render('view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'modelLessons' => $modelLessons,
                    'subjects' => $subjects,
                    'test' => $selected_subjects,
                    'status' => 'added',
                ]);
            } else { //если зашли первый раз
                return $this->render('view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'modelLessons' => $modelLessons,
                    'subjects' => $subjects,
                    'test' => $selected_subjects,
                    'operation' => '',
                    'status' => ''
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