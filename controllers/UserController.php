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
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->orderBy('username ASC'),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

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
}
