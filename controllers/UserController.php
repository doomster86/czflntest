<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\TeacherMeta;
use app\models\StudentMeta;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use app\models\SignupForm;
use app\models\Timetable;
use app\models\Subjects;
use app\models\Groups;
use app\models\Courses;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{

    /**
     * Lists all User models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $request = Yii::$app->request;
        $status = $request->get('status');
        if ($status == 'all') {
            $dataProvider = new ActiveDataProvider([
                'query' => User::find()->orderBy('lastname ASC'),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => User::find()->where(['status' => 1])->orderBy('lastname ASC'),
                'pagination' => [
                    'pageSize' => 9,
                ],
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     *//*
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
*/
	public function actionCreate() {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return $this->render('create', [
                    'model' => $model,
                    'status' => 'added'
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'status' => 'create'
        ]);
    }

    public function actionCreateTeacherMeta()
    {
        $model = new TeacherMeta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateStudentMeta()
    {
        $model = new StudentMeta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->identity->role/*==1*/) {
            $model = $this->findModel($id);
            $teacher = $this->findTeacherModel($id);
            $student = $this->findStudentModel($id);
            $teacher->user_id = $id;
            $student->user_id = $id;

            if($teacher->load(Yii::$app->request->post()) && $teacher->save()) {
                return $this->render('update', [
                    'model' => $model,
                    'teacher' => $teacher,
                    'operation' => 'teacher_updated',
                ]);
            }

            if($student->load(Yii::$app->request->post()) && $student->save()) {
                return $this->render('update', [
                    'model' => $model,
                    'student' => $student,
                    'operation' => 'student_updated',
                ]);
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->render('update', [
                    'model' => $model,
                    'teacher' => $teacher,
                    'student' => $student,
                    'operation' => 'updated',
                ]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'teacher' => $teacher,
                    'student' => $student,
                    'operation' => '',
                ]);
            }

        } else {
            return $this->render('/site/access_denied');
        }
    }

    public function actionUpdateTeacherMeta($id)
    {
        if(Yii::$app->user->identity->role/*==1*/) {
            $model = $this->findTeacherModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
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

    public function actionUpdateStudentMeta($id)
    {
        if(Yii::$app->user->identity->role/*==1*/) {
            $model = $this->findStudentModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
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

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->identity->role==1) {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            return $this->render('/site/access_denied');
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Сторінку не знайдено..');
        }
    }

    protected function findTeacherModel($id)
    {
        if (($model = TeacherMeta::findOne( ['user_id'=>$id] )) !== null) {
            return $model;
        } else {
            $model = new TeacherMeta();
            return $model;
        }
    }

    protected function findStudentModel($id)
    {
        if (($model = StudentMeta::findOne( ['user_id'=>$id] )) !== null) {
            return $model;
        } else {
            $model = new StudentMeta();
            return $model;
        }
    }

    public function actionGodyny($id)
    {
        if (Yii::$app->user->identity->role/*==1*/) {
            $user = User::findOne($id);
            if (Yii::$app->request->post('date')) {
                $datestart = date(1 . '-' . Yii::$app->request->post('date'));
                $dateend = Yii::$app->request->post('date');
                $dateend = date("t", strtotime($datestart)) . '-' . $dateend;
            } else {
                $datestart = date('Y-m-' . 1);
                $dateend = date('Y-m-d');
            }
            $timetables = Timetable::find()
                ->select(['group_id', 'subjects_id'])
                ->where(['teacher_id' => $id])
                ->andWhere(['>=', 'date', strtotime($datestart)])
                ->andWhere(['<=', 'date', strtotime($dateend)])
                ->asArray()
                ->distinct()
                ->all();
            $i = 0;
            $table = array();
            foreach ($timetables as $timetable) {
                $group = Groups::find()->where(['ID' => $timetable['group_id']])->asArray()->one();
                $course = Courses::find()->where(['ID' => $group['course']])->asArray()->one();
                $subject = Subjects::find()->where(['ID' => $timetable['subjects_id']])->asArray()->one();
                if (!empty($group)) {
                    $table[$i] = $timetable;
                    $table[$i]['subject'] = $subject;
                    $table[$i]['group'] = $group;
                    $table[$i]['course'] = $course;
                    $day_count = 1;
                    $lectCompleteAll = 0;
                    for ($j = $day_count; $j <= date('j', strtotime($dateend)); $j++) {
                        $table[$i]['timetable'][$j]['date'] = strtotime(date('Y-m-' . $j, strtotime($dateend)));
                        $lectComplete = Timetable::find()
                            ->asArray()
                            ->select(['COUNT(teacher_id) AS lectCount'])
                            //->where(['>=', 'date', $firstDay]) // date >= $firstDay перепроверить через отладчик все условия с подобнфым синтаксисом
                            //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
                            ->where(['=', 'date', $table[$i]['timetable'][$j]['date']])
                            ->andWhere(['=', 'teacher_id', $user->id])
                            ->andWhere(['=', 'group_id', $group['ID']])
                            ->andWhere(['=', 'half', 2])
                            ->one();
                        $lectComplete = $lectComplete['lectCount'] * 2;
                        $lectCompleteHalf = Timetable::find()
                            ->asArray()
                            ->select(['COUNT(teacher_id) AS lectCount'])
                            //->where(['>=', 'date', $firstDay]) // date >= $firstDay перепроверить через отладчик все условия с подобнфым синтаксисом
                            //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
                            ->where(['=', 'date', $table[$i]['timetable'][$j]['date']])
                            ->andWhere(['=', 'teacher_id', $user->id])
                            ->andWhere(['=', 'group_id', $group['ID']])
                            ->andWhere(['=', 'half', 1])
                            ->one();
                        $lectCompleteHalf = $lectCompleteHalf['lectCount'];

                        $lectComplete = $lectComplete + $lectCompleteHalf;
                        $table[$i]['timetable'][$j]['lectComplete'] = $lectComplete;
                        $lectCompleteAll += $lectComplete;
                    }
                    $table[$i]['lectComplete'] = $lectCompleteAll;
                    $i++;
                }
            }
            return $this->render('godyny', [
                'user' => $user,
                'table' => $table,
                'request' => $dateend,
            ]);
        } else {
            return $this->render('/site/access_denied');
        }
    }
}
