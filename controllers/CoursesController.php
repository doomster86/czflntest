<?php

namespace app\controllers;

use Yii;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\Courses;
use app\models\CoursesSearch;
use app\models\Subjects;
use app\models\Practice;
use yii\helpers\Html;
use app\models\Lessons;
use app\models\LessonsSearch;
use app\models\PracticeLessons;
use app\models\PracticeLessonsSearch;

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
                    'status' => 'created',
                    'operation' => 'created',

                ]);
            } else {
                // либо страница отображается первый раз, либо есть ошибка в данных
                return $this->render('create', [
                    'model' => $model,
                    'status' => '',
                    'operation' => ''
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

            //lessons
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

            //practice

            $searchModelPractice = new PracticeLessonsSearch();
            $params = Yii::$app->request->queryParams;
            $params['practice_id'] = $id;
            $dataProviderPractice = $searchModelPractice->search($params);
            $dataProviderPractice->pagination = ['pageSize' => 15];

            $model = $this->findModel($id);

            $selected_practice = PracticeLessons::find()->asArray()->select('practice_id')->where(['course_id' => $id])->orderBy('practice_id')->all();
            $selected_practice = ArrayHelper::getColumn($selected_practice, 'practice_id');
            $selected_practice = array_combine($selected_practice, $selected_practice);

            $practice_values = Practice::find()->asArray()->select('name')->orderBy('ID')->all();
            $practice_values = ArrayHelper::getColumn($practice_values, 'name');

            $practice_ids = Practice::find()->asArray()->select('ID')->orderBy('ID')->all();
            $practice_ids = ArrayHelper::getColumn($practice_ids, 'ID');

            $practice = array_combine($practice_ids, $practice_values);

            $practice_add = array(0 => 'Оберіть виробничу практику');
            $practice = ArrayHelper::merge($practice_add, $practice);

            $practice = array_diff_key($practice, $selected_practice);

            $modelPracticeLessons = new PracticeLessons();

            //end practice

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
            } elseif ($modelPracticeLessons->load(Yii::$app->request->post()) && $modelPracticeLessons->validate()) {
                $modelPracticeLessons->course_id = $id;
                $modelPracticeLessons->practice_id = Html::encode($modelPracticeLessons->practice_id); //select->option->value
                $modelPracticeLessons->quantity = Html::encode($modelPracticeLessons->quantity);

                $modelPracticeLessons->save(); //запись в таблицу

                $selected_practice = PracticeLessons::find()->asArray()->select('practice_id')->where(['course_id' => $id])->orderBy('practice_id')->all();
                $selected_practice = ArrayHelper::getColumn($selected_practice, 'practice_id');
                $selected_practice = array_combine($selected_practice, $selected_practice);

                $practice_values = Practice::find()->asArray()->select('name')->orderBy('ID')->all();
                $practice_values = ArrayHelper::getColumn($practice_values, 'name');

                $practice_ids = Practice::find()->asArray()->select('ID')->orderBy('ID')->all();
                $practice_ids = ArrayHelper::getColumn($practice_ids, 'ID');

                $practice = array_combine($practice_ids, $practice_values);

                $practice_add = array(0 => 'Оберіть предмет');
                $practice = ArrayHelper::merge($practice_add, $practice);

                $practice = array_diff_key($practice, $selected_practice);

                return $this->render('view', [
                    'searchModelPractice' => $searchModelPractice,
                    'dataProviderPractice' => $dataProviderPractice,
                    'model' => $model,
                    'modelPracticeLessons' => $modelPracticeLessons,
                    'practice' => $practice,
                    'test' => $selected_practice,
                    'status' => 'PAdded',
                ]);
            } else { //если зашли первый раз
                return $this->render('view', [
                    'searchModel' => $searchModel,
                    'searchModelPractice' => $searchModelPractice,
                    'dataProvider' => $dataProvider,
                    'dataProviderPractice' => $dataProviderPractice,
                    'model' => $model,
                    'modelLessons' => $modelLessons,
                    'modelPracticeLessons' => $modelPracticeLessons,
                    'subjects' => $subjects,
                    'practice' => $practice,
                    'test' => $selected_subjects,
                    'operation' => '',
                    'status' => ''
                ]);
            }
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
            //$this->findModel($id)->delete();
            //return $this->redirect(['index']);
            $model = $this->findModel($id);
            return $this->render('delate', [
                'model' => $model,
                'id' =>$id,
            ]);
        } else {
            return $this->render('/site/access_denied');
        }

    }

    public function actionCdelate($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
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
                    'operation' => '',
                ]);
            }
        } else {
            return $this->render('/site/access_denied');
        }

    }

}