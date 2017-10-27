<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\EntryForm;
use app\models\Courses;
use yii\helpers\Html;
use app\models\CoursesSearch;


class ProfController extends Controller
{

    /**
     * Validate CoursesForm
     */
    public function actionCourses()
    {
        $searchModel = new CoursesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 15];

        return $this->render('courses', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function  actionCoursesCreate()
    {
        $model = new Courses();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // данные в $model удачно проверены

            $coursesName = Html::encode($model->name);
            $coursesPract = Html::encode($model->pract);
            $coursesWorklect = Html::encode($model->worklect);
            $coursesTeorlect = Html::encode($model->teorlect);
            $coursesSubject='';
            foreach ($model->subject as $subject) {
                $coursesSubject = $coursesSubject . Html::encode($subject." ");
            }

            $model->name = $coursesName;
            $model->pract = $coursesPract;
            $model->worklect = $coursesWorklect;
            $model->teorlect = $coursesTeorlect;
            $model->subject = $coursesSubject;

            $model->save();

            //return $this->redirect(['courses-create', 'id' => $model->ID]);
            return $this->render('courses-create', ['model' => $model, 'operation' => 'created']);
        } else {
            // либо страница отображается первый раз, либо есть ошибка в данных
            return $this->render('courses-create', ['model' => $model]);
        }
    }

    /**
     * Displays a single Courses model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Courses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Courses::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Сторінку не знайдено.');
        }
    }

    /**
     * Deletes an existing Courses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['courses']);
    }

    /**
     * Updates an existing Courses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $coursesName = Html::encode($model->name);
            $coursesPract = Html::encode($model->pract);
            $coursesWorklect = Html::encode($model->worklect);
            $coursesTeorlect = Html::encode($model->teorlect);
            $coursesSubject='';
            foreach ($model->subject as $subject) {
                $coursesSubject = $coursesSubject . Html::encode($subject." ");
            }

            $model->name = $coursesName;
            $model->pract = $coursesPract;
            $model->worklect = $coursesWorklect;
            $model->teorlect = $coursesTeorlect;
            $model->subject = $coursesSubject;

            $model->update();

            //return $this->redirect(['update', 'id' => $model->ID, 'operation' => 'updated']);
            return $this->render('update', ['model' => $model, 'operation' => 'updated', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

}