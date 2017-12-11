<?php

namespace app\controllers;

use Yii;
use app\models\Subjects;
use app\models\SubjectsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
/**
 * SubjectsController implements the CRUD actions for Subjects model.
 */
class SubjectsController extends Controller {

    /**
     * Lists all Subjects models.
     * @return mixed
     */
    public function actionIndex() {

        if(Yii::$app->user->identity->role==1) {

            $searchModel = new SubjectsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
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

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                return $this->render('create', [
                    'model' => $model,
                    'status' => 'created',
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'status' => ''
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

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $model->update();
                return $this->render('update', [
                    'model' => $model,
                    'status' => 'updated',
                ]);

            } else {
                return $this->render('update', [
                    'model' => $model,
                    'status' => ''
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
