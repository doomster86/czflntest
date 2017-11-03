<?php

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\Courses;
use app\models\Subjects;
use yii\helpers\Html;
use app\models\CoursesSearch;

class ProfController extends Controller
{

    /**
     * Validate CoursesForm
     */
    public function actionCourses()
    {
        if(Yii::$app->user->identity->role==1) {
            $searchModel = new CoursesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->pagination = ['pageSize' => 15];

            return $this->render('courses', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->render('/site/access_denied');
        }
    }

    public function  actionCoursesCreate()
    {
        if(Yii::$app->user->identity->role==1) {
            $model = new Courses();

            $array = Subjects::find()->asArray()->select('name')->orderBy('name')->all();
            $subjects = ArrayHelper::getColumn($array, 'name');

            $subjects_ids = Subjects::find()->asArray()->select('ID')->orderBy('ID')->all(); ///
            $subjects_ids = ArrayHelper::getColumn($subjects_ids, 'ID');

            $subjects = array_combine($subjects_ids,$subjects);

            //$subjects=array('Предмет 1', 'Предмет 2', 'Предмет 3', 'Предмет 4', 'Предмет 5', 'Предмет 6', 'Предмет 7', 'Предмет 8');

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // данные в $model удачно проверены

                $coursesName = Html::encode($model->name);
                $coursesPract = Html::encode($model->pract);
                $coursesWorklect = Html::encode($model->worklect);
                $coursesTeorlect = Html::encode($model->teorlect);
                $coursesSubject ='';
                $subjectCount = count($model->subject);
                $i=0;
                foreach ($model->subject as $subject) {
                    $i++;
                    if($i==$subjectCount) {
                        $coursesSubject = $coursesSubject . Html::encode($subject);
                    } else {
                        $coursesSubject = $coursesSubject . Html::encode($subject.", ");
                    }
                }

                $model->name = $coursesName;
                $model->pract = $coursesPract;
                $model->worklect = $coursesWorklect;
                $model->teorlect = $coursesTeorlect;
                $model->subject = $coursesSubject;

                $model->save();

                //return $this->redirect(['courses-create', 'id' => $model->ID]);
                return $this->render('courses-create', [
                    'model' => $model,
                    'operation' => 'created',
                    'subjects' => $subjects,

                ]);
            } else {
                // либо страница отображается первый раз, либо есть ошибка в данных
                return $this->render('courses-create', [
                    'model' => $model,
                    'subjects' => $subjects,
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
    protected function findModel($id)
    {
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

    /**
     * Deletes an existing Courses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->identity->role==1) {
            $this->findModel($id)->delete();

            return $this->redirect(['courses']);
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

            $array = Subjects::find()->asArray()->select('name')->orderBy('name')->all();
            $subjects = ArrayHelper::getColumn($array, 'name');

            //$subjects=array('Предмет 1', 'Предмет 2', 'Предмет 3', 'Предмет 4', 'Предмет 5', 'Предмет 6', 'Предмет 7', 'Предмет 8');

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $coursesName = Html::encode($model->name);
                $coursesPract = Html::encode($model->pract);
                $coursesWorklect = Html::encode($model->worklect);
                $coursesTeorlect = Html::encode($model->teorlect);
                $coursesSubject='';
                foreach ($model->subject as $subject) {
                    $coursesSubject = $coursesSubject . Html::encode($subject.", ");
                }

                $model->name = $coursesName;
                $model->pract = $coursesPract;
                $model->worklect = $coursesWorklect;
                $model->teorlect = $coursesTeorlect;
                $model->subject = $coursesSubject;

                $model->update();

                //return $this->redirect(['update', 'id' => $model->ID, 'operation' => 'updated']);
                return $this->render('update', [
                    'model' => $model,
                    'operation' => 'updated',
                    'id' => $model->ID,
                    'subjects' => $subjects,
                ]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'subjects' => $subjects,
                ]);
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }

}