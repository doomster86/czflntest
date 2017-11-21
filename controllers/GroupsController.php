<?php

namespace app\controllers;

use Yii;
use app\models\Groups;
use app\models\GroupsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use app\models\Courses;
use yii\helpers\ArrayHelper;
use app\models\User;
/**
 * GroupsController implements the CRUD actions for Groups model.
 */
class GroupsController extends Controller
{
    /**
     * @inheritdoc
     */
    /*
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
     * Lists all Groups models.
     * @return mixed
     */
    public function actionIndex() {
	    if(Yii::$app->user->identity->role==1) {
	        $searchModel = new GroupsSearch();
		    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		    $dataProvider->pagination = ['pageSize' => 15];

		    return $this->render('index', [
			    'searchModel' => $searchModel,
			    'dataProvider' => $dataProvider,
		    ]);
	    } else {
		    return $this->render('/site/access_denied');
	    }
    }

    /**
     * Displays a single Groups model.
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
     * Creates a new Groups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
	    if(Yii::$app->user->identity->role==1) {
	        $model = new Groups();

            $curators_ids = User::find()->asArray()->select('id')->orderBy('id')->where(['role' => 2])->all();
            $curators_ids = ArrayHelper::getColumn($curators_ids, 'id');

            $curators_firstnames = User::find()->asArray()->select('firstname')->orderBy('id')->where(['role' => 2])->all();
            $curators_firstnames = ArrayHelper::getColumn($curators_firstnames, 'firstname');

            $curators_lastnames = User::find()->asArray()->select('lastname')->orderBy('id')->where(['role' => 2])->all();
            $curators_lastnames = ArrayHelper::getColumn($curators_lastnames, 'lastname');

            $curators_values = array();
            for ($i=0; $i  < count($curators_firstnames); $i++) {
                $curators_values[] = $curators_firstnames[$i].' '.$curators_lastnames[$i];
            }

            $curators = array_combine($curators_ids, $curators_values);


		    $curators_add = array(0 => 'Оберіть куратора');
		    $curators = ArrayHelper::merge($curators_add, $curators);

		    $courses_ids = Courses::find()->asArray()->select('ID')->orderBy('ID')->all();
		    $courses_ids = ArrayHelper::getColumn($courses_ids, 'ID');

		    $courses_values = Courses::find()->asArray()->select('name')->orderBy('ID')->all();
		    $courses_values = ArrayHelper::getColumn($courses_values, 'name');

		    $courses = array_combine($courses_ids, $courses_values);

		    $courses_add = array(0 => 'Оберіть професію');
		    $courses = ArrayHelper::merge($courses_add, $courses);


	        if ($model->load(Yii::$app->request->post()) && $model->save()) {

	            return $this->render('create', [
	            	'id' => $model->ID,
		            'model' => $model,
		            'courses' => $courses,
		            'curators' => $curators,
		            'status' => 'created',
	            ]);
	        } else {
	            return $this->render('create', [
	                'model' => $model,
		            'courses' => $courses,
		            'curators' => $curators,
	            ]);
	        }
	    } else {
		    return $this->render('/site/access_denied');
	    }
    }

    /**
     * Updates an existing Groups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
	    if(Yii::$app->user->identity->role==1) {
	        $model = $this->findModel($id);

            $curators_ids = User::find()->asArray()->select('id')->orderBy('id')->where(['role' => 2])->all();
            $curators_ids = ArrayHelper::getColumn($curators_ids, 'id');

            $curators_firstnames = User::find()->asArray()->select('firstname')->orderBy('id')->where(['role' => 2])->all();
            $curators_firstnames = ArrayHelper::getColumn($curators_firstnames, 'firstname');

            $curators_lastnames = User::find()->asArray()->select('lastname')->orderBy('id')->where(['role' => 2])->all();
            $curators_lastnames = ArrayHelper::getColumn($curators_lastnames, 'lastname');

            $curators_values = array();
            for ($i=0; $i  < count($curators_firstnames); $i++) {
                $curators_values[] = $curators_firstnames[$i].' '.$curators_lastnames[$i];
            }

            $curators = array_combine($curators_ids, $curators_values);

		    $curators_add = array(0 => 'Оберіть куратора');
		    $curators = ArrayHelper::merge($curators_add, $curators);

		    $courses_ids = Courses::find()->asArray()->select('ID')->orderBy('ID')->all();
		    $courses_ids = ArrayHelper::getColumn($courses_ids, 'ID');

		    $courses_values = Courses::find()->asArray()->select('name')->orderBy('ID')->all();
		    $courses_values = ArrayHelper::getColumn($courses_values, 'name');

		    $courses = array_combine($courses_ids, $courses_values);

		    $courses_add = array('Оберіть професію');
		    $courses = ArrayHelper::merge($courses_add, $courses);

	        if ($model->load(Yii::$app->request->post()) && $model->save()) {

		        return $this->render('update', [
			        'model' => $model,
			        'courses' => $courses,
			        'curators' => $curators,
			        'status' => 'updated',
		        ]);

	        } else {

	            return $this->render('update', [
	                'model' => $model,
		            'courses' => $courses,
		            'curators' => $curators,
	            ]);
	        }
	    } else {
		    return $this->render('/site/access_denied');
	    }
    }

    /**
     * Deletes an existing Groups model.
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
     * Finds the Groups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Groups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
	    if(Yii::$app->user->identity->role==1) {
	        if (($model = Groups::findOne($id)) !== null) {
	            return $model;
	        } else {
	            throw new NotFoundHttpException('The requested page does not exist.');
	        }
	    } else {
		    return $this->render('/site/access_denied');
	    }
    }
}
