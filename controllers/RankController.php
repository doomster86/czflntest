<?php

namespace app\controllers;

use Yii;
use app\models\Rank;
use app\models\RankSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RankController implements the CRUD actions for Rank model.
 */
class RankController extends Controller {


    /**
     * Lists all Rank models.
     * @return mixed
     */
    public function actionIndex() {
        if(Yii::$app->user->identity->role==1) {
            $searchModel = new RankSearch();
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
     * Creates a new Rank model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(){
        if(Yii::$app->user->identity->role==1) {
            $model = new Rank();
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
     * Updates an existing Rank model.
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
     * Deletes an existing Rank model.
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
     * Finds the Rank model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rank the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if(Yii::$app->user->identity->role==1) {
            if (($model = Rank::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }
}
