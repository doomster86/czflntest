<?php

namespace app\controllers;

use app\models\Groups;
use app\models\Lessons;
use app\models\Subjects;
use app\models\User;
use Yii;
use app\models\TimetableParts;
use app\models\TimetablePartsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Timetable;

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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionFreetime()
    {
        $request = Yii::$app->request;
        $group = $request->get('group');
        $subject = $request->get('subject');
        $teacher = $request->get('teacher');
        if(!empty($group) && !empty($subject) && !empty($teacher)) {

            $group_name = $this->getGroupName($group);
            $subject_name = $this->getSubjectName($subject);
            $teacher_name = $this->getTeacherName($teacher);
            $course_id = $this->getCourseId($group);
            $lessons_all = $this->getAllLessons($course_id, $subject);
            $lessons_in_table = $this->getLessonsInTable($subject, $group);
            $lessons_more = $lessons_all - $lessons_in_table;

            return $this->render('freetime', [
                'group' => $group_name,
                'subject' => $subject_name,
                'teacher' => $teacher_name,
                'lessons' => $lessons_all,
                'intable' => $lessons_in_table,
                'more' => $lessons_more,
            ]);
        } else {
            return $this->redirect(['index']);
        }
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
        $subject_values = Subjects::find()->asArray()
            ->select(['ID', 'name'])
            ->where(['=', 'ID', $id])
            ->one();

        $subject = $subject_values['name'];

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
}
