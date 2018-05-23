<?php

namespace app\controllers;

use Yii;
use app\models\Skill;
use app\models\SkillSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SkillController implements the CRUD actions for Skill model.
 */
class SkillController extends Controller {

    /**
     * Lists all Skill models.
     * @return mixed
     */
    public function actionIndex() {
        if(Yii::$app->user->identity->role==1) {
            $searchModel = new SkillSearch();
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
     * Creates a new Skill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if(Yii::$app->user->identity->role==1) {
            $model = new Skill();

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
     * Updates an existing Skill model.
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
     * Deletes an existing Skill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        if(Yii::$app->user->identity->role==1) {
            $model = $this->findModel($id);
            $teachers = Skill::getTeachers($id);
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
     * Finds the Skill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Skill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if(Yii::$app->user->identity->role==1) {
            if (($model = Skill::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            return $this->render('/site/access_denied');
        }
    }
}
