<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "timetable_parts".
 *
 * @property integer $id
 * @property integer $datestart
 * @property integer $dateend
 * @property integer $cols
 * @property integer $rows
 *
 * @property Timetable[] $timetables
 */
class TimetableParts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'timetable_parts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datestart', 'dateend'], 'required'],
            [['cols', 'rows'], 'safe'],
            [['cols', 'rows'], 'integer'],
            [['dateend', 'datestart'], 'validateDateend'],
        ];
    }

    public function validateDateend($attribute, $params)
    {
        $datestart = strtotime($this->datestart);
        $dateend = strtotime($this->dateend);

        if ($dateend < $datestart) {
            $this->addError($attribute, 'Дата кінця не може бути меншою за дату початку');
        }

        $timetableParts = $this->find()
            ->asArray()
            ->select('datestart, dateend')
            ->all();

        $dateStartAr = ArrayHelper::getColumn($timetableParts, 'datestart');
        $dateEndAr = ArrayHelper::getColumn($timetableParts, 'dateend');
        $arrLenght = count($dateStartAr);

        for ($i = 0; $i < $arrLenght; $i++) {
            if($datestart <= $dateEndAr[$i] && $dateend >= $dateStartAr[$i]) {
                $this->addError($attribute, 'Ці дати попадають в вже існуючий період. Оберіть інші дати.');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datestart' => 'Дата початку',
            'dateend' => 'Дата кінця',
            'cols' => 'Cols',
            'rows' => 'Rows',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimetables()
    {
        return $this->hasMany(Timetable::className(), ['corps_id' => 'id']);
    }

    public function seveTimetableParts() {
        $datestart = strtotime($this->datestart);
        $dateend = strtotime($this->dateend);

        $this->datestart = $datestart;
        $this->dateend = $dateend;

        $lecturesCounter = LectureTable::find()
            ->asArray()
            ->select(['COUNT(corps_id) AS corps_id'])
            ->groupBy(['corps_id'])
            ->all();
        $lecturesCounter = max($lecturesCounter);
        $lecturesCounter = $lecturesCounter['corps_id'];

        $this->cols = ($dateend - $datestart)/(60*60*24)+1;
        $this->rows = $lecturesCounter;
        $this->save();
        $this->generate($datestart, $dateend, $lecturesCounter);
    }

    //рекурсивная функция?
    //точка входа - дата начада генерации
    //точка выхода - ? дата концка генерации, нет свободных ячеек, больше нельзя поставить лекции, не нарушая правила
    public function generate($datestart, $dateend, $rows) {

        $lecturesCounter = LectureTable::find()
            ->asArray()
            ->select(['COUNT(corps_id) AS lessons, corps_id'])
            ->groupBy(['corps_id'])
            ->all();

        v($lecturesCounter);

        //генерим сетку и проходимся по ней, вставля туда пары
        //день
        while ($datestart <= $dateend) {
            //номер лекции
            for($i = 1; $i <= $rows; $i++){
                //все группы
                $groups = new Groups();
                $allGroups = $groups->find()
                    ->asArray()
                    ->all();

                //Проходим по всем группам
                foreach ($allGroups as $group) {
                    $groupID = $group['ID'];
                    $groupName = $group['name'];
                    $courseID = $group['course'];

                    //получаем все предметы текущей группы
                    $groupLessons = Lessons::find()
                        ->asArray()
                        ->where(['course_id' => $courseID])
                        ->all();

                    //пробуем поставить предмет в ячейку, если не подходит, пробуем следующий и т.д.
                    foreach ($groupLessons as $lesson) {
                        //узнаём аудиторию лекции

                        //узнаём обязательна ли она

                        //узнаём корпус

                        //узнаём преподавателя этой лекции
                        $teacherID = Subjects::find()
                            ->asArray()
                            ->select('teacher_id')
                            ->where(['ID' => $lesson['subject_id']])
                            ->one();

                        //узнаём работает ли он в этот день
                        $formatter = new \yii\i18n\Formatter;
                        $day = $formatter->asDate($datestart, "l"); //текущий день недели
                        $workStatus = 0;
                        switch ($day) {
                            case 'Monday':
                                $workStatus = TeacherMeta::find()
                                    ->asArray()
                                    ->select('monday')
                                    ->where(['user_id' => $teacherID['teacher_id']])
                                    ->one();
                                $workStatus = ArrayHelper::getValue($workStatus, 'monday');
                                break;
                            case 'Tuesday':
                                $workStatus = TeacherMeta::find()
                                    ->asArray()
                                    ->select('tuesday')
                                    ->where(['user_id' => $teacherID['teacher_id']])
                                    ->one();
                                $workStatus = ArrayHelper::getValue($workStatus, 'tuesday');
                                break;
                            case 'Wednesday':
                                $workStatus = TeacherMeta::find()
                                    ->asArray()
                                    ->select('wednesday')
                                    ->where(['user_id' => $teacherID['teacher_id']])
                                    ->one();
                                $workStatus = ArrayHelper::getValue($workStatus, 'wednesday');
                                break;
                            case 'Thursday':
                                $workStatus = TeacherMeta::find()
                                    ->asArray()
                                    ->select('thursday')
                                    ->where(['user_id' => $teacherID['teacher_id']])
                                    ->one();
                                $workStatus = ArrayHelper::getValue($workStatus, 'thursday');
                                break;
                            case 'Friday':
                                $workStatus = TeacherMeta::find()
                                    ->asArray()
                                    ->select('friday')
                                    ->where(['user_id' => $teacherID['teacher_id']])
                                    ->one();
                                $workStatus = ArrayHelper::getValue($workStatus, 'friday');
                                break;
                            case 'Saturday':
                                $workStatus = TeacherMeta::find()
                                    ->asArray()
                                    ->select('saturday')
                                    ->where(['user_id' => $teacherID['teacher_id']])
                                    ->one();
                                $workStatus = ArrayHelper::getValue($workStatus, 'saturday');
                                break;
                            case 'Sunday':
                                $workStatus = TeacherMeta::find()
                                    ->asArray()
                                    ->select('sunday')
                                    ->where(['user_id' => $teacherID['teacher_id']])
                                    ->one();
                                $workStatus = ArrayHelper::getValue($workStatus, 'sunday');
                                break;
                        }

                        //узнаём сколько у текущего корпуса может быть лекций
                        $maxLesson = 1;

                        //если преподаватель работает
                        if($workStatus == 1) {
                            //если номер пары не больше, чем может быть для корпуса, в котором будет проходить занятие
                            if($maxLesson <= $i){

                            }
                        }
                    }
                }
            }
            $datestart = $datestart + 86400;
            //self::generate($datestart, $dateend, $rows);
        }

        /**
        //все группы
        $groups = new Groups();
        $allGroups = $groups->find()
            ->asArray()
            ->all();

        $lecturesMas = array(); // массив, в который будем помещать лекции для расписания

        //Стартовая точка - проходим по всем группам
        foreach ($allGroups as $group) {
            $groupID = $group['ID'];
            $groupName = $group['name'];
            $courseID = $group['course'];

            //получаем все предметы текущей группы
            $groupLessons = Lessons::find()
                ->asArray()
                ->where(['course_id' => $courseID])
                ->all();

            //пробуем поставить пару в ячейку
            //проходимся по всем предметам и тот, которые возможно, ставим на этот день на эту пару
            //в зависимости от того, свободна ли аудитория, работает ли в этот день преподаватель и свободен ли он
            //первая лекция определяет в каком корпусе в этот день у группы будут проходить занятия
            foreach ($groupLessons as $lesson) {
                //узнаём преподавателя этой лекции
                $teacherID = Subjects::find()
                    ->asArray()
                    ->select('teacher_id')
                    ->where(['ID' => $lesson['subject_id']])
                    ->one();

                //узнаём работает ли он в этот день
                $day = $formatter->asDate($datestart, "l");
            }

        }
        **/

    }
}
