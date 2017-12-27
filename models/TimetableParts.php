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
                                //первое занятие у группы ставить производтсвенное обучение
                                //смотрим сколько всего у группы уже было занятий
                                $first = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(id) AS lectCount'])
                                    ->where(['=', 'group_id', $groupID])
                                    ->all();

                                if($first['lectCount'] == 0) {
                                    //узнаём тип лекции, если это не практика, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                    $type = Subjects::find()
                                        ->asArray()
                                        ->select('practice')
                                        ->where(['ID' => $lesson['subject_id']])
                                        ->one();

                                    if($type['practice'] == 0) {
                                        continue;
                                    }
                                }

                                //узнаём аудиторию лекции
                                $audienceID = Subjects::find()
                                    ->asArray()
                                    ->select('audience_id')
                                    ->where(['ID' => $lesson['subject_id']])
                                    ->one();
                                $audienceID = $audienceID['audience_id'];

                                //узнаём корпус аудитории
                                $currentCorpsId = Audience::find()
                                    ->asArray()
                                    ->select('corps_id')
                                    ->where(['ID' => $audienceID])
                                    ->one();
                                $currentCorpsId = $currentCorpsId['corps_id'];

                                $subjId = $lesson['subject_id'];

                                //проверяем, чтобы у группы не было в этот день занятий в разных корпусах
                                $sameCorps = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(id) AS counter'])
                                    ->where(['!=', 'corps_id', $currentCorpsId])
                                    ->andWhere(['=', 'date', $datestart])
                                    ->andWhere(['=', 'group_id', $groupID])
                                    ->all();

                                //если есть занятия в другом корпусе, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                if($sameCorps['counter'] != 0) {
                                    continue;
                                }

                                //узнаём преподавателя этой лекции
                                $teacherID = Subjects::find()
                                    ->asArray()
                                    ->select('teacher_id')
                                    ->where(['ID' => $subjId])
                                    ->one();
                                $teacherID = $teacherID['teacher_id'];

                                //узнаём работает ли он в этот день
                                $formatter = new \yii\i18n\Formatter;
                                $day = $formatter->asDate($datestart, "l"); //текущий день недели
                                $workStatus = 0;
                                switch ($day) {
                                    case 'Monday':
                                        $workStatus = TeacherMeta::find()
                                            ->asArray()
                                            ->select('monday')
                                            ->where(['user_id' => $teacherID])
                                            ->one();
                                        $workStatus = ArrayHelper::getValue($workStatus, 'monday');
                                        break;
                                    case 'Tuesday':
                                        $workStatus = TeacherMeta::find()
                                            ->asArray()
                                            ->select('tuesday')
                                            ->where(['user_id' => $teacherID])
                                            ->one();
                                        $workStatus = ArrayHelper::getValue($workStatus, 'tuesday');
                                        break;
                                    case 'Wednesday':
                                        $workStatus = TeacherMeta::find()
                                            ->asArray()
                                            ->select('wednesday')
                                            ->where(['user_id' => $teacherID])
                                            ->one();
                                        $workStatus = ArrayHelper::getValue($workStatus, 'wednesday');
                                        break;
                                    case 'Thursday':
                                        $workStatus = TeacherMeta::find()
                                            ->asArray()
                                            ->select('thursday')
                                            ->where(['user_id' => $teacherID])
                                            ->one();
                                        $workStatus = ArrayHelper::getValue($workStatus, 'thursday');
                                        break;
                                    case 'Friday':
                                        $workStatus = TeacherMeta::find()
                                            ->asArray()
                                            ->select('friday')
                                            ->where(['user_id' => $teacherID])
                                            ->one();
                                        $workStatus = ArrayHelper::getValue($workStatus, 'friday');
                                        break;
                                    case 'Saturday':
                                        $workStatus = TeacherMeta::find()
                                            ->asArray()
                                            ->select('saturday')
                                            ->where(['user_id' => $teacherID])
                                            ->one();
                                        $workStatus = ArrayHelper::getValue($workStatus, 'saturday');
                                        break;
                                    case 'Sunday':
                                        $workStatus = TeacherMeta::find()
                                            ->asArray()
                                            ->select('sunday')
                                            ->where(['user_id' => $teacherID])
                                            ->one();
                                        $workStatus = ArrayHelper::getValue($workStatus, 'sunday');
                                        break;
                                }

                                //если преподаватель не работает в этот день, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                if($workStatus == 0) {
                                    continue;
                                }

                                //проверяем, чтобы у преподавателя не было в этот день занятий в разных корпусах
                                $sameCorps = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(id) AS counter'])
                                    ->where(['!=', 'corps_id', $currentCorpsId])
                                    ->andWhere(['=', 'date', $datestart])
                                    ->andWhere(['=', 'teacher_id', $teacherID])
                                    ->all();

                                //если есть занятия в другом корпусе, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                if($sameCorps['counter'] != 0) {
                                    continue;
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

                                //максимальное кол-во часов в неделю для преподавателя
                                $lectMax = TeacherMeta::find()
                                    ->asArray()
                                    ->select('hours')
                                    ->where(['user_id' => $teacherID])
                                    ->one();

                                //если больше нормы, то то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                if($lectComplete >= $lectMax['hours']) {
                                    continue;
                                }

                                //проверяем, нет ли такой же пары в этот день у этой же группы, в следующий или предыдущий день в этой же группы
                                //параметры следует отрегулировать, чтобы не было пустых дней, когда уже почти никих пар поставить нельзя
                                //если есть, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                $sameLect = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(id) AS sameLect'])
                                    ->where(['=', 'date', $datestart])
                                    ->andWhere(['=', 'date', $datestart - 86400])
                                    ->andWhere(['=', 'date', $datestart + 86400])
                                    ->andWhere(['=', 'group_id', $groupID])
                                    ->all();

                                if($sameLect['sameLect'] > 0) {
                                    continue;
                                }

                                //проверяем, если в этот день у группы практические занятия, если есть, то не ставим больше лекций в этот день
                                $lectionsToday = Timetable::find()
                                    ->asArray()
                                    ->select('subjects_id')
                                    ->where(['=', 'date', $datestart])
                                    ->andWhere(['=', 'group_id', $groupID])
                                    ->all();

                                foreach ($lectionsToday as $lection) {
                                    $isPrectice = Subjects::find()
                                        ->asArray()
                                        ->select('practice')
                                        ->where(['=', 'ID', $lection['subjects_id']])
                                        ->one();
                                    //если уже есть практика в этот день, то сразу выходим на следующий день
                                    if($isPrectice['practice'] == 1) {
                                        continue 3; //обрываем foreach ($lectionsToday as $lection), foreach ($groupLessons as $lesson), while ($datestart <= $dateend)
                                    }
                                }

                                //проверяем чтобы не ставить лекций этого предмета больше, чем можно максимально в неделю
                                $inWeek = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(subjects_id) AS subjInWeek'])
                                    ->where(['>=', 'date', $firstMonday])
                                    ->andWhere(['<=', 'date', $firstMonday + 518400]) //понедельник + 6 дней
                                    ->andWhere(['=', 'group_id', $groupID])
                                    ->one();

                                $maxInWeek = Subjects::find()
                                    ->asArray()
                                    ->select('max_week')
                                    ->where(['=', 'ID', $subjId])
                                    ->one();
                                //если на этой неделе предмета больше или равно макс. кол-ву в неделю, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                if($inWeek['subjInWeek'] >= $maxInWeek['max_week']) {
                                    continue;
                                }

                                //проверяем чтобы не ставить лекций этого предмета больше, чем всего максимально возможно
                                $allCurrentSubj = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(subjects_id) AS subj'])
                                    ->where(['=', 'group_id', $groupID])
                                    ->one();

                                $maxSubj = Lessons::find()
                                    ->asArray()
                                    ->select('quantity')
                                    ->where(['=', 'course_id', $courseID])
                                    ->andWhere(['=', 'subject_id', $subjId])
                                    ->one();
                                //если предмета больше или равно макс. кол-ву, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                if($allCurrentSubj['subj'] >= $maxSubj['quantity']) {
                                    continue;
                                }

                                //наконец-то прошли все проверки и делаем запись в базу


                            } //цикл по лекциям
                        } //цикл по всем группам

                        //сегодня в завтрашний день
                        $datestart = (int)$datestart + 86400;
                } //цикл по дням
            } //цикл по парам
        } //цикл по корпусам
    }
}
