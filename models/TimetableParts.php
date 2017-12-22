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
        $this->generate($datestart, $dateend);
    }

    public function generate($datestart, $dateend) {
        $timetable = new Timetable();
        //$datestart = $this->datestart;
        //$dateend =$this->dateend;

        //все преподаватели users (role 2)
        $teachers = new User;
        $allTeachers = $teachers->find()
            ->asArray()
            ->where(['role' => 2])
            ->all();

        //Рабочие дни преподавателей и максимальная нагрузка часов в неделю
        $teacherMeta = new TeacherMeta();
        $allTeacherMeta = $teacherMeta->find()
            ->asArray()
            ->select('id, user_id, hours, monday, tuesday, wednesday, thursday, friday, saturday, sunday')
            ->all();


        //все завершённые лекции count_lecture

        //все лекции, закреплённые за профессиями lessons и оставшееся их кол-во
        $lessons = new Lessons();
        $allLessons = $lessons->find()
            ->asArray()
            ->all();

        //вся практика и осташееся её кол-во practice_lessons
        $practiceLessons = new PracticeLessons();
        $allPracticeLessons = $practiceLessons->find()
            ->asArray()
            ->all();

        //желаемые\обязательные аудитории для лекций subjects, макс. число в неделю
        $subjects = new Subjects();
        $allSubjects = $subjects->find()
            ->asArray()
            ->all();

        //желаемые\обязательные аудитории для практики practice, макс. число в неделю
        $practice = new Practice();
        $allPractice = $practice->find()
            ->asArray()
            ->all();

        //все корпуса corps

        //все пары lecture_table (1-я пара в корпусе 1, 2-я пара в корпусе 2... 4-я пара в корпусе 2)
        $lectureTable = new LectureTable();
        $allLectureTable = $lectureTable->find()
            ->asArray()
            ->all();

        //все аудитории audience
        $audience = new Audience();
        $allAudience = $audience->find()
            ->asArray()
            ->all();

        //все группы
        $groups = new Groups();
        $allGroups = $groups->find()
            ->asArray()
            ->all();

        $lecturesMas = array(); // массив, в который будем помещать лекции для расписания

        //Стартовая точка - генерация расписания для группы
        foreach ($allGroups as $group) {
            $groupID = $group['ID'];
            $groupName = $group['name'];
            $courseID = $group['course'];
            $date = $datestart; //первая дата расписания

            //получаем все предметы этой группы
            $groupLessons = Lessons::find()
                ->asArray()
                ->where(['course_id' => $courseID])
                ->all();

            //делаем расписание группе на день
            foreach ($groupLessons as $lesson) {

            }

        }

    }
}
