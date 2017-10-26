<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\EntryForm;
use app\models\CoursesForm;
use yii\data\Pagination;
use app\models\Courses;
use yii\helpers\Html;


class ProfController extends Controller
{

    /**
     * Validate CoursesForm
     */
    public function actionCourses()
    {
        $query = Courses::find();

        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);

        $courses = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $model = new CoursesForm();

        return $this->render('courses', ['courses' => $courses, 'pagination' => $pagination]);
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

}