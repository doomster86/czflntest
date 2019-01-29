<?php

namespace app\controllers;

use app\models\Audience;
use app\models\Corps;
use app\models\LectureTable;
use app\models\Timetable;
use app\models\Nakaz;
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

            $curators_ids = User::find()->asArray()->select('id')->orderBy('lastname')->where(['role' => 2])->all();
            $curators_ids = ArrayHelper::getColumn($curators_ids, 'id');

            $curators_firstnames = User::find()->asArray()->select('firstname')->orderBy('lastname')->where(['role' => 2])->all();
            $curators_firstnames = ArrayHelper::getColumn($curators_firstnames, 'firstname');

            $curators_lastnames = User::find()->asArray()->select('lastname')->orderBy('lastname')->where(['role' => 2])->all();
            $curators_lastnames = ArrayHelper::getColumn($curators_lastnames, 'lastname');

            $curators_values = array();
            for ($i=0; $i  < count($curators_firstnames); $i++) {
                $curators_values[] = $curators_lastnames[$i].' '.$curators_firstnames[$i];
            }

            $curators = array_combine($curators_ids, $curators_values);


		    $curators_add = array(0 => 'Оберіть куратора');
		    $curators = ArrayHelper::merge($curators_add, $curators);

		    $courses_ids = Courses::find()->asArray()->select('ID')->orderBy('name')->all();
		    $courses_ids = ArrayHelper::getColumn($courses_ids, 'ID');

		    $courses_values = Courses::find()->asArray()->select('name')->orderBy('name')->all();
		    $courses_values = ArrayHelper::getColumn($courses_values, 'name');

		    $courses = array_combine($courses_ids, $courses_values);

		    $courses_add = array(0 => 'Оберіть професію');
		    $courses = ArrayHelper::merge($courses_add, $courses);


            if ($model->load(Yii::$app->request->post())) {
                $model->date_start = strtotime($model->date_start);
                $model->date_end = strtotime($model->date_end);
                $model->date_dka_1 = strtotime($model->date_dka_1);
                $model->date_dka_2 = strtotime($model->date_dka_2);
                if ($model->save()) {
                    return $this->render('create', [
                        'id' => $model->ID,
                        'model' => $model,
                        'courses' => $courses,
                        'curators' => $curators,
                        'status' => 'created',
                    ]);
                }
            } else {
	            return $this->render('create', [
	                'model' => $model,
		            'courses' => $courses,
		            'curators' => $curators,
                    'status' => ''
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

            if ($model->load(Yii::$app->request->post())) {
                $model->date_start = strtotime($model->date_start);
                $model->date_end = strtotime($model->date_end);
                $model->date_dka_1 = strtotime($model->date_dka_1);
                $model->date_dka_2 = strtotime($model->date_dka_2);
                if ($model->save()) {
                    return $this->render('update', [
                        'model' => $model,
                        'courses' => $courses,
                        'curators' => $curators,
                        'status' => 'updated',
                    ]);
                }
	        } else {

	            return $this->render('update', [
	                'model' => $model,
		            'courses' => $courses,
		            'curators' => $curators,
                    'status' => ''
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

    public function actionGetListGroup() {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $groups = Groups::find()->select(["id" => "ID", "name"])->orderBy("name")->asArray()->all();
        if (count($groups) > 0) {
            return $groups;
        } else {
            return array('error' => 100);
        }
    }

    public function actionGetShedGroup()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $request = Yii::$app->request->get();
        if (!empty($request['group']) && !empty($request['datestart']) && !empty($request['datefin'])) {
            $group_id = intval($request['group']);
            if (!is_int($group_id) || $group_id < 1) {
                return array('error' => 303);
            }
            $hasGroup = Groups::find()->where(['ID' => $group_id])->exists();
            if (!$hasGroup) {
                return array('error' => 101);
            }
            $datestart = strtotime($request['datestart']);
            $datefin = strtotime($request['datefin']);
            if (!$datestart) {
                return array('error' => 301);
            } else if (!$datefin) {
                return array('error' => 302);
            }
            $timetable = Timetable::find()
                ->asArray()
                ->where(['=', 'group_id', $group_id])
                ->andWhere(['>=', 'date', $datestart])
                ->andWhere(['<=', 'date', $datefin])
                ->all();
            $subjects = array();
            $i = 0;
            if (count($timetable) > 0) {
                foreach ($timetable as $lesson) {
                    $subjects[$i]['id'] = $lesson['id'];
                    $corps = Corps::find()->asArray()->select(["name" => 'CONCAT(corps_name, ", ", location)'])->where(['ID' => $lesson['corps_id']])->one();
                    $subjects[$i]['corps'] = $corps['name'];
                    $audience = Audience::find()->asArray()->select(["name" => 'CONCAT(num)'])->where(['ID' => $lesson['audience_id']])->one();
                    $subjects[$i]['audience'] = $audience['name'];
                    $subjects[$i]['subject'] = $lesson['title'];
                    $teacher = User::find()->where(['=', 'id', $lesson['teacher_id']])->select(['name' => 'CONCAT(lastname, " ", firstname, " ", middlename)'])->asArray()->one();
                    $subjects[$i]['teacher'] = $teacher['name'];
                    $group = Groups::find()->where(['=', 'ID', $lesson['group_id']])->select(['name' => 'CONCAT(name)'])->asArray()->one();
                    $subjects[$i]['group'] = $group['name'];
                    $subjects[$i]['lecture'] = $lesson['y'];
                    $subjects[$i]['date'] = date('d.m.Y', $lesson['date']);
                    $subjects[$i]['half'] = $lesson['half'];
                    $lectures = LectureTable::find()->where(['=', 'corps_id', $lesson['corps_id']])->asArray()->all();
                    $subjects[$i]['time'] = $lectures[$lesson['y'] - 1]['time_start'];
                    $i++;
                }
                return $subjects;
            } else {
                return array('error' => 310);
            }
        } else {
            return array('error' => 300);
        }
    }
}
