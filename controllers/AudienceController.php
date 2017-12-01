<?php

namespace app\controllers;

use Yii;
use app\models\Audience;
use app\models\AudienceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \app\models\Corps;
use yii\helpers\ArrayHelper;
/**
 * AudienceController implements the CRUD actions for Audience model.
 */
class AudienceController extends Controller {

    /**
     * Lists all Audience models.
     * @return mixed
     */
    public function actionIndex() {
        if(Yii::$app->user->identity->role==1) {
            $searchModel = new AudienceSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            //$modelCorps = new Corps();
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                //'model_corps' => $modelCorps,
            ]);
        } else {
            return $this->render('/site/access_denied');
        }
    }

    /**
     * Creates a new Audience model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(){
        if(Yii::$app->user->identity->role==1) {

            $model = new Audience();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                return $this->render('create', [
                    'model' => $model,
                    'status' => 'created'
                ]);

            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }

    /**
     * Updates an existing Audience model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        if(Yii::$app->user->identity->role==1) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                //return $this->redirect(['view', 'id' => $model->ID]);
                //$model->update();
                return $this->render('update', [
                    'model' => $model,
                    'status' => 'updated'
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
     * Deletes an existing Audience model.
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
     * Finds the Audience model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Audience the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if(Yii::$app->user->identity->role==1) {
            if (($model = Audience::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('Аудиторія не знайдена.');
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }


}
