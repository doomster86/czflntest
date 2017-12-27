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
    //$datestart - дата начала генерации, $dateend - дата конца генерациии
    public function generate($datestart, $dateend) {

        $datestart = (int)$datestart;
        $dateend = (int)$dateend;

        $lecturesCounter = LectureTable::find()
            ->asArray()
            ->select(['COUNT(corps_id) AS lessons, corps_id'])
            ->groupBy(['corps_id'])
            ->all();

        //массив ключ-значение, где ключ - id корпуса, значение - максимальное кол-во лекция в этом корпусе
        $corpsLect = array();

        //генерим сетку и проходимся по ней, вставля туда пары

        //проходимся по корпусам, у каждого корпуса своё макс. кол-во пар в день
        foreach ($lecturesCounter as $corps) {

            //определяем текущий корпус и его кол-во пар
            $corpsID = $corps['corps_id'];
            $lectCount = $corps['lessons'];

            //проходимся по всем парам
            for ($i = 1; $i <= $lectCount; $i++) {

                //проходимся по дням
                while ($datestart <= $dateend) {
                    //$i - текущий номер пары,
                    //$corpsID - текущий id корпуса
                    //$datestart - текущая дата

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
                            //если не можем поставить без окна, то заканчиваем день
                            //перебираем лекции
                            foreach ($groupLessons as $lesson) {
                                //узнаём аудиторию лекции
                                $audienceID = Subjects::find()
                                    ->asArray()
                                    ->select('audience_id')
                                    ->where(['ID' => $lesson['subject_id']])
                                    ->one();

                                //узнаём корпус аудитории
                                $currentCorpsId = Audience::find()
                                    ->asArray()
                                    ->select('corps_id')
                                    ->where(['ID' => $audienceID])
                                    ->one();

                                //если аудитория не из этого корпуса, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                if( $currentCorpsId['corps_id'] != $corpsID) {
                                    break;
                                }

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

                                //если преподаватель не работает в этот день, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                if($workStatus == 0) {
                                    break;
                                }

                                //считаем сколько преподаватель наработал часов на этой неделе,
                                //если больше нормы, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                //для начала, определяем дату первого понедельника в этой неделе генерируемого расписания
                                $firstMonday = $datestart;
                                $day = $formatter->asDate($firstMonday, "l");
                                while ($day != 'Monday') {
                                    $firstMonday = $firstMonday - 86400;
                                    $day = $formatter->asDate($firstMonday, "l");
                                }

                                //начиная с первого понедельника до воскресенья, считаем количество пар, которые провёл преподаватель
                                //!!! надо будет переписать эту часть для более точного учёта, т.к. сейчас все лекции в этой таблицебудут считаться как состоявшиеся
                                $lectComplete = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(teacher_id) AS lectCount'])
                                    ->where(['>=', 'date', $firstMonday])
                                    ->andWhere(['<=', 'date', $firstMonday + 518400]) //понедельник + 6 дней
                                    ->groupBy(['teacher_id'])
                                    ->all();

                                //кол-во часов, которое преподватель проработал уже, одна пара - два академических часа
                                $lectComplete = $lectComplete['lectCount']*2;

                                $lectMax = TeacherMeta::find()
                                    ->asArray()
                                    ->select('hours')
                                    ->where(['user_id' => $teacherID['teacher_id']])
                                    ->one();

                                if($lectComplete >= $lectMax['hours']) {
                                    break;
                                }

                                //проверяем, нет ли такой же пары в этот день у этой же группы, в следующий или предыдущий день в этой же группы
                                //если есть, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                $sameLect = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(id) AS sameLect'])
                                    ->where(['=', 'date', $datestart])
                                    ->andWhere(['=', 'date', $datestart - 86400])
                                    ->andWhere(['=', 'date', $datestart + 86400])
                                    ->all();

                                if($sameLect['sameLect'] > 0) {
                                    break;
                                }

                                //проверяем, чтобы у преподавателя не было в этот день занятий в разных корпусах

                                //проверяем, чтобы у группы не было в этот день занятий в разных корпусах

                                //проверяем, если в этот день у группы практические занятия, если есть, то не ставим больше лекций в этот день

                                //первое занятие у группы ставить вводное

                            } //цикл по лекциям
                        } //цикл по всем группам

                        //сегодня в завтрашний день
                        $datestart = (int)$datestart + 86400;
                } //цикл по дням
            } //цикл по парам
        } //цикл по корпусам
    }
}
