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
class SubjectsController extends Controller
{

    /**
     * Lists all Subjects models.
     * @return mixed
     */
    public function actionAllSubjects()
    {
        $searchModel = new SubjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('subjects', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new Subjects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subjects();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $subjectName = Html::encode($model->name);

            $model->name = $subjectName;

            $model->save();

            return $this->render('create', [
                'model' => $model,
                'operation' => 'created',
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Subjects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subjects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subjects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Сторінку не знайдено.');
        }
    }

    /**
     * Deletes an existing Subjects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['all-subjects']);
    }

    /**
     * Updates an existing Subjects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $subjectName = Html::encode($model->name);

            $model->name = $subjectName;

            $model->update();

            return $this->render('update', [
                'model' => $model,
                'operation' => 'updated',
                'id' => $model->ID,
            ]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

}
