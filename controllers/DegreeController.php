<?php

namespace app\controllers;

use Yii;
use app\models\Degree;
use app\models\DegreeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DegreeController implements the CRUD actions for Degree model.
 */
class DegreeController extends Controller {

    /**
     * Lists all Degree models.
     * @return mixed
     */
    public function actionIndex() {
        if(Yii::$app->user->identity->role==1) {
            $searchModel = new DegreeSearch();
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
     * Creates a new Degree model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if(Yii::$app->user->identity->role==1) {
            $model = new Degree();
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->render('create', [
                    'model' => $model,
                    'status' => 'created'
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
     * Updates an existing Degree model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        if(Yii::$app->user->identity->role==1) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->render('update', [
                    'model' => $model,
                    'status' => 'updated'
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
     * Deletes an existing Degree model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id){
        if(Yii::$app->user->identity->role==1) {
            $model = $this->findModel($id);
            $teachers = Degree::getTeachers($id);
            if($teachers != NULL) {
                return $this->render('delate', [
                    'model' => $model,
                    'id' =>$id,
                ]);
            } else {
                $this->findModel($id)->delete();
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }

    /**
     * Finds the Degree model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Degree the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id){
        if(Yii::$app->user->identity->role==1) {
            if (($model = Degree::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }
}
