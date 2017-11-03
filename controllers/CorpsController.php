<?php

namespace app\controllers;

use Yii;
use app\models\Corps;
use app\models\CorpsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CorpsController implements the CRUD actions for Corps model.
 */
class CorpsController extends Controller {

    /**
     * Lists all Corps models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CorpsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Corps model.
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
     * Creates a new Corps model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Corps();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //редирект на страницу элемента
            //return $this->redirect(['view', 'id' => $model->ID]);

            //просто редирект, поля формы очищаются
            //return $this->redirect('create');

            return $this->render('create', [
                'model' => $model,
                'status' => 'created'
            ]);


        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Corps model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //return $this->redirect(['view', 'id' => $model->ID]);
            $model->update();
            return $this->render('update', [
                'model' => $model,
                'status' => 'updated'
            ]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Corps model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Corps model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Corps the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Corps::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Корпус не знайдено');
        }
    }
}
