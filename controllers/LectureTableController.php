<?php

namespace app\controllers;

use Yii;
use app\models\LectureTable;
use app\models\LectureTableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \app\models\Corps;
/**
 * LectureTableController implements the CRUD actions for LectureTable model.
 */
class LectureTableController extends Controller
{
    /**
     * @inheritdoc
     *//*
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
*/
    /**
     * Lists all LectureTable models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new LectureTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LectureTable model.
     * @param integer $id
     * @return mixed

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    */

    /**
     * Creates a new LectureTable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if(Yii::$app->user->identity->role==1) {

            $model = new LectureTable();

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
     * Updates an existing LectureTable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {

        $searchModel = new LectureTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->ID]);

            return $this->render('update', [
                'model' => $model,
                'status' => 'updated',
                'dataProvider' => $dataProvider,

            ]);

        } else {
            return $this->render('update', [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing LectureTable model.
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
     * Finds the LectureTable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LectureTable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LectureTable::findOne($id)) !== null) {
            return $model;
        } else {
	        $model = new LectureTable();
	        return $model;
        }
    }
}
