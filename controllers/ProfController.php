<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\EntryForm;
use app\models\CoursesForm;
use yii\data\Pagination;
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

        return $this->render('courses', [
            'courses' => $courses,
            'pagination' => $pagination,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function  actionCoursesCreate()
    {
        $model = new CoursesForm();

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

            $newcours = new Courses();

            $newcours->name = $coursesName;
            $newcours->pract = $coursesPract;
            $newcours->worklect = $coursesWorklect;
            $newcours->teorlect = $coursesTeorlect;
            $newcours->subject = $coursesSubject;

            $newcours->save();


            //return $this->render('courses-confirm', ['model' => $model]);
            return $this->render('courses-create', ['model' => $model]);
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}