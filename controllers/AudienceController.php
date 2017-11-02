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
    public function actionIndex()
    {
        $searchModel = new AudienceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Audience model.
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
     * Creates a new Audience model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Audience();
        $corps_add = array('Оберіть корпус');
        $corps = Corps::find()->asArray()->select('name')->orderBy('ID')->all();
        $corps = ArrayHelper::getColumn($corps, 'name');

        $corps = ArrayHelper::merge($corps_add, $corps);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->render('create', [
                'model' => $model,
                'corps' => $corps,
                'status' => 'created'
            ]);

        } else {
            return $this->render('create', [
                'model' => $model,
                'corps' => $corps
            ]);
        }
    }

    /**
     * Updates an existing Audience model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        $corps_add = array('Оберіть корпус');
        $corps = Corps::find()->asArray()->select('name')->orderBy('ID')->all();
        $corps = ArrayHelper::getColumn($corps, 'name');

        $corps = ArrayHelper::merge($corps_add, $corps);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //return $this->redirect(['view', 'id' => $model->ID]);
            $model->update();
            return $this->render('update', [
                'model' => $model,
                'corps' => $corps,
                'status' => 'updated'
            ]);

        } else {
            return $this->render('update', [
                'model' => $model,
                'corps' => $corps,
            ]);
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
        if (($model = Audience::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Аудиторія не знайдена.');
        }
    }

    public function v($var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }

}
