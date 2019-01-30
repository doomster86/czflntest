<?php

namespace app\controllers;

use app\models\LectureTable;
use app\models\Subjects;
use app\models\TimetableViewer;
use Yii;
use app\models\Timetable;
use app\models\TimetableCreator;
use app\models\TimetableSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * TimetableController implements the CRUD actions for Timetable model.
 */
class TimetableController extends Controller
{

    /**
     * Lists all Timetable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TimetableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new Timetable();

	    $TTViewer = new TimetableViewer();

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
	        'TTViewer' => $TTViewer,
        ]);
    }

    /**
     * Displays a single Timetable model.
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
     * Creates a new Timetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Timetable();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

	        $teacherID = Subjects::find()
	                             ->asArray()
	                             ->select('teacher_id')
	                             ->where(['ID' => $model->subjects_id])
	                             ->one();
	        $teacherID = $teacherID['teacher_id'];

	        $model->teacher_id = $teacherID;

	        $lectureIDs = LectureTable::find()
	                                  ->asArray()
	                                  ->select(["ID"])
	                                  ->where(['=', 'corps_id', $model->corps_id])
	                                  ->orderBy('time_start')
	                                  ->all();
	        $lectureIDs = ArrayHelper::getColumn($lectureIDs, 'ID');
	        $lectureID = $lectureIDs[$model->y];

	        $model->lecture_id = $lectureID;

        	$model->save();

            return $this->redirect(['/timetable-parts/view?id='.$model->part_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Timetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Timetable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	    $request = Yii::$app->request;
    	$tp = $request->get('tp');
	    $this->findModel($id)->delete();

        return $this->redirect(['/timetable-parts/view?id='.$tp]);
    }

    /**
     * Finds the Timetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Timetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Timetable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSubcat() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $corpsId = $parents[0];

                $out = Timetable::getAudienceList($corpsId);
                /*
                $out = [
                        ['id'=>'2', 'name'=>'<prod-name1>'],
                        ['id'=>'3', 'name'=>'<prod-name2>'],
                        ['id'=>'4', 'name'=>'<prod-name3>'],
                ];
                */

                return Json::encode(['output'=>$out, 'selected'=>'']);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionSubcatlecture() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $corpsId = $parents[0];

                $out = Timetable::getLectureList($corpsId);

                return Json::encode(['output'=>$out, 'selected'=>'']);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionSubcatsubjects() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $groupId = $parents[0];

                $out = Timetable::getGroupList($groupId);

                return Json::encode(['output'=>$out, 'selected'=>'']);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionPrint($table_id)
    {
        return $this->render('print', [
            'table_id' => $table_id,
        ]);
    }
}
