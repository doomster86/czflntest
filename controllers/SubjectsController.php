<?php

namespace app\controllers;

use Yii;
use app\models\Subjects;
use app\models\SubjectsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
/**
 * SubjectsController implements the CRUD actions for Subjects model.
 */
class SubjectsController extends Controller
{

    /**
     * Lists all Subjects models.
     * @return mixed
     */
    public function actionIndex() {

        if(Yii::$app->user->identity->role==1) {

            $searchModel = new SubjectsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            //$modelTeachers = new Teacher();
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                //'model_teachers' => $modelTeachers,
            ]);

        } else {
            return $this->render('/site/access_denied');
        }
    }


    /**
     * Creates a new Subjects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if(Yii::$app->user->identity->role==1) {

            $model = new Subjects();
/*
            $teachers_add = array( 0 => 'Оберіть викладача');

            $teachers = array(
                'Свирид Опанасович' => 'Свирид Опанасович',
                'Мурзік Васильович' => 'Мурзік Васильович',
                'Пророк Самуїл' => 'Пророк Самуїл'
            );
*/
            //добавить подгрузку из класса Teacher
            /*
            $teachers_values = Teachers::find()->asArray()->select('name')->orderBy('ID')->all();
            $teachers_values = ArrayHelper::getColumn($teachers_values, 'name');

            $teachers_ids = Teachers::find()->asArray()->select('ID')->orderBy('ID')->all();
            $teachers_ids = ArrayHelper::getColumn($teachers_ids, 'ID');

            $teachers = array_combine($teachers_ids,$teachers_values);

            $teachers = ArrayHelper::merge($teachers_add, $teachers);
*/
            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                return $this->render('create', [
                    'model' => $model,
                    //'teachers' => $teachers,
                    'status' => 'created',
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'status' => ''
                    //'teachers' => $teachers,
                ]);
            }

        } else {
            return $this->render('/site/access_denied');
        }
    }

    /**
     * Updates an existing Subjects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        if(Yii::$app->user->identity->role==1) {
            $model = $this->findModel($id);
            /*
            $teachers_add = array('Оберіть викладача');

            $teachers = array(
                'Свирид Опанасович' => 'Свирид Опанасович',
                'Мурзік Васильович' => 'Мурзік Васильович',
                'Пророк Самуїл' => 'Пророк Самуїл'
            );

             из модели/таблицы Teachers
            $teachers = Teachers::find()->asArray()->select('name')->orderBy('ID')->all();
            $teachers = ArrayHelper::getColumn($teachers, 'name');
               
            $teachers = ArrayHelper::merge($teachers_add, $teachers);
            */
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $model->update();
                return $this->render('update', [
                    'model' => $model,
                    'status' => 'updated',
                    //'teachers' => $teachers,
                    
                ]);

            } else {
                return $this->render('update', [
                    'model' => $model,
                    'status' => ''
                    //'teachers' => $teachers,
                ]);
            }
        } else {
            return $this->render('/site/access_denied');
        }

    }

    /**
     * Deletes an existing Subjects model.
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
     * Finds the Subjects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subjects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if(Yii::$app->user->identity->role==1) {
            if (($model = Subjects::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('Сторінку не знайдено.');
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }

}
