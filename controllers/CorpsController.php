<?php

namespace app\controllers;

use Yii;
use app\models\Corps;
use app\models\CorpsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\db\IntegrityException;
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
        if(Yii::$app->user->identity->role==1) {
            $searchModel = new CorpsSearch();
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
     * Creates a new Corps model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->identity->role==1) {
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
                    'status' => ''
                ]);
            }
        } else {
            return $this->render('/site/access_denied');
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
        if(Yii::$app->user->identity->role==1) {
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
                    'status' => ''
                ]);
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }

    /**
     * Deletes an existing Corps model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        if(Yii::$app->user->identity->role==1) {
            $model = $this->findModel($id);
            $audience = Corps::getAudience($id);
            $lecture = Corps::getLecture($id);
            if($audience == NULL && $lecture == NULL) {
                $this->findModel($id)->delete();
                return $this->redirect(['index']);
            } else {
                return $this->render('delate', [
                    'model' => $model,
                    'id' =>$id,
                ]);
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }

    /**
     * Finds the Corps model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Corps the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if(Yii::$app->user->identity->role==1) {
            if (($model = Corps::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('Корпус не знайдено');
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }
}
