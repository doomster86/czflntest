<?php

namespace app\controllers;

use app\models\AddGroupToTable;
use app\models\Groups;
use app\models\Lessons;
use app\models\Modules;
use app\models\RnpSubjects;
use app\models\Subjects;
use app\models\User;
use Yii;
use app\models\TimetableParts;
use app\models\TimetablePartsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Timetable;
use app\models\Courses;
use app\models\TimetableViewer;

/**
 * TimetablePartsController implements the CRUD actions for TimetableParts model.
 */
class TimetablePartsController extends Controller
{
    /**
     * Lists all TimetableParts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TimetablePartsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TimetableParts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $timetable = new Timetable();
        $TTViewer = new TimetableViewer();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'timetable' => $timetable,
            'TTViewer' => $TTViewer,
        ]);
    }

    public function actionFreetime()
    {
        $request = Yii::$app->request;
        $group = $request->get('group');
        $subject = $request->get('subject');
        $teacher = $request->get('teacher');
        $date = $request->get('date');
        if(!empty($group) && !empty($subject) && !empty($teacher) && !empty($date)) {

            $group_name = $this->getGroupName($group);
            $subject_name = $this->getSubjectName($subject);
            $teacher_name = $this->getTeacherName($teacher);
            $course_id = $this->getCourseId($group);
            $modules = Modules::find()->asArray()->where(['subject_id' => $subject])->all();
            $allrnp = 0;
            foreach ($modules as $module) {
                $allrnp += $module['column_plan'] * $module['column_rep'];
            }
            $allrozklad = $this->getLessonsInTable($subject, $group);

            $weekrnp = $this->getWeekRnp($subject, $group, $date);
            $weekrozklad = $this->getWeekRozklad($subject, $group, $date);

            return $this->render('freetime', [
                'group' => $group_name,
                'subject' => $subject_name,
                'teacher' => $teacher_name,
                'allrnp' => $allrnp,
                'allrozklad' => $allrozklad,
                'weekrnp' => $weekrnp,
                'weekrozklad' => $weekrozklad,
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function getWeekRozklad($subject_id, $group_id, $date) {
        $group = Groups::find()->asArray()->where(['ID' => $group_id])->one();
        $date_start = $group['date_start'];
        $date_diff = $date - $group['date_start'];
        $num_week =  ceil(date('d', $date_diff)/7);
        $firstDay = $date_start + $date_diff + (86400*7*($num_week-1));
        $lastDay = $date_start + $date_diff + (86400*7*($num_week-1)) + (86400*7);
        $lessonsInTable = Timetable::find()
            ->asArray()
            ->select('half')
            ->where(['=', 'subjects_id', $subject_id])
            ->andWhere(['=', 'group_id', $group_id])
            ->andWhere(['>=', 'date', $firstDay])
            ->andWhere(['<', 'date', $lastDay])
            ->all();

        $sum = 0;

        foreach ($lessonsInTable as $lesson) {
            $sum = $sum + $lesson['half'];
        }

        return $sum;
    }
    public function getWeekRnp($subject_id, $group_id, $date) {
        $group = Groups::find()->asArray()->where(['ID' => $group_id])->one();
        $date_diff = $date - $group['date_start'];
        $num_week =  ceil(date('d', $date_diff)/7);
        $modules = Modules::find()
            ->where(['subject_id' => $subject_id])
            ->asArray()
            ->all();
        $column_rep = 0;
        $column_plan = 0;
        foreach ($modules as $module) {
            $column_rep += $module['column_rep'];
            if ($num_week <= $column_rep) { // если неделя попадает в РНП, присваиваем часы
                $column_plan += $module['column_plan'];
                break;
            }
        }
        return $column_plan;
    }

    /**
     * Creates a new TimetableParts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TimetableParts();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /*
            * получаем дату начала и конца генерации расписания
            * формируем сетку
            * - формируем кол-во столбцов по количеству дней от начала до конца дат генерации
            * - формируем кол-во строк по максимальному количеству пар среди корпусов
            * записываем в тиблицу timetable_parts даты начала и конца генерации расписания, количество строки и столбцов
            */

            /*
            * внутри метода вызываем другой, который по заданным правилам наполняет сетку занятиями
            * указывает координаты ячейки расписания в сетке с данным id
            * Правила формирования:
            *  - первой парой в курсе у группы всегда ставить вводную лекцию
            *  - у группы и у преподавателя не могут быть пары в один день в разных корпусах
            *  - если есть производственная практика, то в этот день других занятий не ставить
            *  - учитывать максимальную нагрузку преподавателей в неделю и не превышать её
            *  - учитывать рабочие дни преподавателей и не ставить им пары в другие дни недели
            *  - не превышать заданное кол-во занятий по указанному предмету
            *  - не ставить один и тот же предмет в один день несколько раз
            *  - по возможности, ставить одинаковые предметы через день
            */
            $model->seveTimetableParts();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TimetableParts model.
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
     * Deletes an existing TimetableParts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Timetable::deleteAll(['=', 'part_id', $id]);

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionAddgroup($id, $gid) {

        $model = TimetableParts::findOne($id);
        $datestart = $model->datestart;
        $dateend = $model->dateend;
        $cols = $model->cols;
        $rows = $model->rows;
        $mont = (int)date('mY', $datestart);
        $timetable = new Timetable();
        $TTViewer = new TimetableViewer();

        //TimetableParts::generateLectures($datestart, $dateend, $cols, $rows, $mont, $gid);
        TimetableParts::generateLecturesRnps($datestart, $dateend, $cols, $rows, $mont, $gid);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'timetable' => $timetable,
            'TTViewer' => $TTViewer,
        ]);
    }

    /**
     * Finds the TimetableParts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TimetableParts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TimetableParts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getGroupName($id) {
        $group_values = Groups::find()->asArray()
            ->select(['ID', 'name'])
            ->where(['=', 'ID', $id])
            ->one();

        $group = $group_values['name'];

        return $group;
    }

    public function getCourseId($id) {
        $course_values = Groups::find()->asArray()
            ->select(['ID', 'course'])
            ->where(['=', 'ID', $id])
            ->one();

        $course = $course_values['course'];

        return $course;
    }

    public function getSubjectName($id) {
        $subject_values = Timetable::find()->asArray()
            ->select(['subjects_id', 'title'])
            ->where(['=', 'subjects_id', $id])
            ->one();

        $subject = $subject_values['title'];

        return $subject;
    }

    public function getTeacherName($id) {
        $teacherName = User::find()
            ->asArray()
            ->select('firstname, middlename, lastname')
            ->where(['=', 'id', $id])
            ->one();
        $teacherName = $teacherName['firstname'] . " " . $teacherName['lastname'];

        return $teacherName;
    }

    public function getAllLessons($course_id, $subject_id) {
        $quantity = Lessons::find()
            ->asArray()
            ->select('quantity')
            ->where(['=', 'course_id', $course_id])
            ->andWhere(['=', 'subject_id', $subject_id])
            ->one();
        $allLessons = $quantity['quantity'];

        return $allLessons;
    }

    public function getLessonsInTable($subject_id, $group_id) {
        $lessonsInTable = Timetable::find()
            ->asArray()
            ->select('half')
            ->where(['=', 'subjects_id', $subject_id])
            ->andWhere(['=', 'group_id', $group_id])
            ->all();

        $sum = 0;

        foreach ($lessonsInTable as $lesson) {
            $sum = $sum + $lesson['half'];
        }

        return $sum;
    }

    public function actionGodyny()
    {
        if (Yii::$app->user->identity->role/*==1*/) {
            if (Yii::$app->request->post('date')) {
                $datestart = date(1 . '-' . Yii::$app->request->post('date'));
                $dateend = Yii::$app->request->post('date');
                $dateend = date("t", strtotime($datestart)) . '-' . $dateend;
            } else {
                $datestart = date('Y-m-' . 1);
                $dateend = date('Y-m-d');
            }
            $teacher_id = Yii::$app->request->post('User');
            $teacher_id = $teacher_id['ID'];
            $group_id = Yii::$app->request->post('Groups');
            $group_id = $group_id['ID'];
            $timetables = Timetable::find()
                ->select(['group_id', 'subjects_id', 'teacher_id'])
                ->filterWhere(['teacher_id' => $teacher_id])
                ->andFilterWhere(['group_id' => $group_id])
                ->andWhere(['>=', 'date', strtotime($datestart)])
                ->andWhere(['<=', 'date', strtotime($dateend)])
                ->asArray()
                ->distinct()
                ->orderBy('teacher_id')
                ->all();
            $i = 0;
            $table = array();
            foreach ($timetables as $timetable) {
                $teacher = User::find()->where(['ID' => $timetable['teacher_id']])->asArray()->one();
                $group = Groups::find()->where(['ID' => $timetable['group_id']])->asArray()->one();
                $course = Courses::find()->where(['ID' => $group['course']])->asArray()->one();
                $subject = RnpSubjects::find()->where(['ID' => $timetable['subjects_id']])->asArray()->one();
                $table[$i]['teacher'] = $teacher;
                $table[$i]['group'] = $group;
                $table[$i]['course'] = $course;
                $table[$i]['subject'] = $subject;
                $day_count = 1;
                $lectCompleteAll = 0;
                for ($j = $day_count; $j <= date('j', strtotime($dateend)); $j++) {
                    $date = strtotime(date('Y-m-' . $j, strtotime($dateend)));
                    $lectComplete = Timetable::find()
                        ->asArray()
                        ->select(['COUNT(teacher_id) AS lectCount'])
                        //->where(['>=', 'date', $firstDay]) // date >= $firstDay перепроверить через отладчик все условия с подобнфым синтаксисом
                        //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
                        ->where(['=', 'date', $date])
                        ->andWhere(['=', 'teacher_id', $teacher['id']])
                        ->andWhere(['=', 'group_id', $group['ID']])
                        ->andWhere(['=', 'half', 2])
                        ->one();
                    $lectComplete = $lectComplete['lectCount'] * 2;
                    $lectCompleteHalf = Timetable::find()
                        ->asArray()
                        ->select(['COUNT(teacher_id) AS lectCount'])
                        //->where(['>=', 'date', $firstDay]) // date >= $firstDay перепроверить через отладчик все условия с подобнфым синтаксисом
                        //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
                        ->where(['=', 'date', $date])
                        ->andWhere(['=', 'teacher_id', $teacher['id']])
                        ->andWhere(['=', 'group_id', $group['ID']])
                        ->andWhere(['=', 'half', 1])
                        ->one();
                    $lectCompleteHalf = $lectCompleteHalf['lectCount'];

                    $lectComplete = $lectComplete + $lectCompleteHalf;
                    if ((date('w', $date) == 0 || date('w', $date) == 6) && $lectComplete == 0) {
                        continue;
                    }
                    $table[$i]['timetable'][$j]['date'] = $date;
                    $table[$i]['timetable'][$j]['lectComplete'] = $lectComplete;
                    $lectCompleteAll += $lectComplete;
                }
                $table[$i]['lectComplete'] = $lectCompleteAll;
                $i++;
            }
            return $this->render('godyny', [
                'table' => $table,
            ]);
        } else {
            return $this->render('/site/access_denied');
        }
    }
}
