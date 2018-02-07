<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Timetable;

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

        $cols = ($dateend - $datestart)/(60*60*24)+1;
        $this->cols = $cols;

        $rows = $lecturesCounter;
        $this->rows = $rows;
        $this->save();

        $this->generateLectures($datestart, $dateend, $cols, $rows);
    }

    public function generateLectures($datestart, $dateend, $cols, $rows) {
        $datestart = (int)$datestart;
        $dateend = (int)$dateend;

        $id = TimetableParts::find()
            ->asArray()
            ->select('id')
            ->where(['=', 'datestart', $datestart])
            ->one();
        $id = $id['id'];

        //все группы
        $groups = new Groups();
        $allGroups = $groups->find()
            ->asArray()
            ->all();

        //обход по всем группам
        foreach ($allGroups as $group) {
            $groupID = $group['ID'];
            $groupName = $group['name'];
            $courseID = $group['course'];

            //echo "группа";
            //v($groupID);

            //получаем все предметы текущей группы
            $groupLessons = Lessons::find()
                ->asArray()
                ->where(['course_id' => $courseID])
                ->all();


            //обход по дням
            for ($i = 0; $i < $cols; $i++ ) {
                //координата номера дня
                $x = $i + 1;

                //определяем текущею дату
                $date = $datestart + 86400 * $i;

                //echo "день";
                $formatter = new \yii\i18n\Formatter;
                //v($formatter->asDate($date, "dd.MM.yyyy"));

                //обход по парам
                for($j = 0; $j < $rows; $j++) {
                    //координата номера пары
                    $y = $j + 1;

                    //echo "пара";
                    //v($y);


                    //пробуем поставить предмет в ячейку, если не подходит, пробуем следующий и т.д.
                    //если не можем поставить без окна, то заканчиваем день
                    //перебираем лекции группы
                    foreach ($groupLessons as $lesson) {
                        global $lectFilterStatus;
                        $lectFilterStatus = 1; //по умолчанию, считаем что можем поставить лекцию

                        $subjId = $lesson['subject_id'];

                        //echo "предмет";
                        //v($subjId);

                        //узнаём преподавателя этой лекции
                        $teacherID = Subjects::find()
                            ->asArray()
                            ->select('teacher_id')
                            ->where(['ID' => $subjId])
                            ->one();
                        $teacherID = $teacherID['teacher_id'];

                        //узнаём аудиторию лекции
                        $audienceID = Subjects::find()
                            ->asArray()
                            ->select('audience_id')
                            ->where(['ID' => $subjId])
                            ->one();
                        $audienceID = $audienceID['audience_id'];

                        //узнаём корпус аудитории
                        $currentCorpsId = Audience::find()
                            ->asArray()
                            ->select('corps_id')
                            ->where(['ID' => $audienceID])
                            ->one();
                        $currentCorpsId = $currentCorpsId['corps_id'];

                        //узнаём тип занятия (практика\не практика)
                        $type = Subjects::find()
                            ->asArray()
                            ->select('practice')
                            ->where(['ID' => $subjId])
                            ->one();
                        $type = $type['practice'];

                        //далее идёт проверка по группе правил, которые запрещают ставить лекцию в ячейку

                        //нельзя ставить пару, если у корпуса их меньше $y
                        if($lectFilterStatus == 1) {
                            global $lecturesCounterCorps;
                            $lecturesCounterCorps = LectureTable::find()
                                ->asArray()
                                ->select(['COUNT(corps_id) AS corps_id'])
                                ->where(['=', 'corps_id', $currentCorpsId])
                                ->one();
                            $lecturesCounterCorps = $lecturesCounterCorps['corps_id'];
                            if($lecturesCounterCorps < $y ) {
                                //echo "У корпуса не может быть пар в один день больше чем.".$y."<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить первым занятием практику
                        if($lectFilterStatus == 1) {
                            global $first;
                            //смотрим сколько всего у группы уже было занятий
                            $first = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(id) AS lectCount'])
                                ->where(['=', 'group_id', $groupID])
                                ->one();
                            $first = $first['lectCount'];

                            //если занятий ещё не было
                            if ($first == 0) {
                                //если это практика, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                if ($type == 1) {
                                    //echo "Первый предмет не может быть практикой<br/>";
                                    $lectFilterStatus = 0;
                                }
                            }
                        }

                        //нельзя ставить практику студентам вместе с обычными лекциями в один день
                        if($lectFilterStatus == 1) {
                            //если практика
                            if($type == 1) {
                                global $lectInThisDateGroup;
                                //узнаём были ли лекции в этот день у группы, если да, то практику нельзя ставить группе
                                $lectInThisDateGroup = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(id) AS lectCount'])
                                    ->where(['=', 'date', $date])
                                    ->andWhere(['=', 'group_id', $groupID])
                                    ->one();
                                $lectInThisDateGroup = $lectInThisDateGroup['lectCount'];

                                if ($lectInThisDateGroup > 0) {
                                    //echo "Нельзя ставить практику группе, потому что уже были лекции в этот день<br/>";
                                    $lectFilterStatus = 0;
                                }
                            }
                        }

                        //нельзя ставить практику преподавателю вместе с обычными лекциями в один день
                        if($lectFilterStatus == 1) {
                            global $lectInThisDateTeacher;
                            //если практика
                            if($type == 1) {
                                //узнаём были ли лекции в этот день у преподавателя, если да, то практику нельзя ставить преподавателю
                                $lectInThisDateTeacher = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(id) AS lectCount'])
                                    ->where(['=', 'date', $date])
                                    ->andWhere(['=', 'teacher_id', $teacherID])
                                    ->one();
                                $lectInThisDateTeacher = $lectInThisDateTeacher['lectCount'];

                                if($lectInThisDateTeacher > 0 ) {
                                    //echo "Нельзя ставить практику преподавателю, потому что уже были леции в этот день<br/>";
                                    $lectFilterStatus = 0;
                                }
                            }
                        }

                        //нельзя ставить студентам занятия в разных корпусах в один день
                        if($lectFilterStatus == 1) {
                            global $sameCorps;
                            //проверяем, чтобы у группы не было в этот день занятий в разных корпусах
                            $sameCorps = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(id) AS counter'])
                                ->where(['!=', 'corps_id', $currentCorpsId])
                                ->andWhere(['=', 'date', $date])
                                ->andWhere(['=', 'group_id', $groupID])
                                ->all();
                            $sameCorps = $sameCorps['counter'];

                            //если есть занятия в другом корпусе, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            if ($sameCorps != 0) {
                                //echo "Нельзя ставить группе занятия в разных корпусах в один день<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить преподавателю занятия в разных корпусах в один день
                        if($lectFilterStatus == 1) {
                            global $sameCorps;
                            //проверяем, чтобы у преподавателя не было в этот день занятий в разных корпусах
                            $sameCorps = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(id) AS counter'])
                                ->where(['!=', 'corps_id', $currentCorpsId])
                                ->andWhere(['=', 'date', $date])
                                ->andWhere(['=', 'teacher_id', $teacherID])
                                ->all();
                            $sameCorps = $sameCorps['counter'];

                            //если есть занятия в другом корпусе, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            if ($sameCorps != 0) {
                                //echo "Нельзя ставить преподавателю занятия в разных корпусах в один день<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить преподавателю занятия в его нерабочий день
                        if($lectFilterStatus == 1) {
                            global $workStatus;
                            //узнаём работает ли преподаватель в этот день
                            $formatter = new \yii\i18n\Formatter;
                            $day = $formatter->asDate($date, "l"); //текущий день недели
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
                            if ($workStatus == 0) {
                                //echo "Преподаватель в этот день не работает<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить преподавателю больше занятий в неделю, чем позволяет норматив
                        if($lectFilterStatus == 1) {
                            global $lectComplete;
                            global $lectMax;
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
                                ->where(['>=', 'date', $firstMonday]) // date >= $firstMonday
                                ->andWhere(['<=', 'date', $firstMonday + 518400])//date <= понедельник+6 дней
                                ->groupBy(['teacher_id'])
                                ->one();

                            //кол-во часов, которое преподатель проработал уже, одна пара - два академических часа
                            $lectComplete = $lectComplete['lectCount'] * 2;

                            //максимальное кол-во часов в неделю для преподавателя
                            $lectMax = TeacherMeta::find()
                                ->asArray()
                                ->select('hours')
                                ->where(['user_id' => $teacherID])
                                ->one();

                            //если больше нормы, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            if ($lectComplete >= $lectMax['hours']) {
                                //echo "Преподаватель уже отработал норму в неделю<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить преподавателю больше занятий в календарный месяц, чем позволяет норматив
                        if($lectFilterStatus == 1) {
                            global $lectComplete;
                            global $lectMax;
                            //считаем сколько преподаватель наработал часов на этой неделе,
                            //если больше нормы, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            //для начала, определяем дату первого и последнего дня месяца
                            $firstDay = date('01.m.Y', $datestart);
                            $lastDay = date('Y.m.t', $datestart);
                            $firstDay = strtotime($firstDay);
                            $lastDay = strtotime($lastDay);

                            //начиная с первого дня месяца по последний день месяца, считаем количество пар, которые провёл преподаватель
                            //!!! надо будет переписать эту часть для более точного учёта, т.к. сейчас все лекции в этой таблице будут считаться как состоявшиеся
                            $lectComplete = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(teacher_id) AS lectCount'])
                                ->where(['>=', 'date', $firstDay]) // date >= $firstDay
                                ->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
                                ->andWhere(['=', 'teacher_id', $teacherID])
                                ->groupBy(['teacher_id'])
                                ->one();

                            //кол-во часов, которое преподатель проработал уже, одна пара - два академических часа
                            $lectComplete = $lectComplete['lectCount'] * 2;

                            //максимальное кол-во часов в календарный месяц для преподавателя
                            $lectMax = TeacherMeta::find()
                                ->asArray()
                                ->select('montshours')
                                ->where(['user_id' => $teacherID])
                                ->one();

                            //если больше нормы, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            if ($lectComplete >= $lectMax['montshours']) {
                                //echo "Преподаватель уже отработал норму в месяц<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить студентам одну и ту же пару в один и тот же день несколько раз
                        if($lectFilterStatus == 1) {
                            global $sameLect;
                            //проверяем, нет ли такой же пары в этот день у этой же группы, в следующий или предыдущий день в этой же группы
                            //параметры следует отрегулировать, чтобы не было пустых дней, когда уже почти никих пар поставить нельзя
                            //если есть, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            $sameLect = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(id) AS sameLect'])
                                ->where(['=', 'date', $date])
                                //->andWhere(['=', 'date', $date - 86400]) ограничение на такую же пару в предыдущий день
                                //->andWhere(['=', 'date', $date + 86400]) ограничение на такую же пару на следующий день
                                ->andWhere(['=', 'group_id', $groupID])
                                ->andWhere(['=', 'subjects_id', $subjId])
                                ->one();
                            $sameLect = $sameLect['sameLect'];

                            if ($sameLect > 0) {
                                //echo "Нельзя ставить одинаковые пары в один день<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить студентам лекции в один день с практикой
                        if($lectFilterStatus == 1) {
                            global $isPrectice;
                            //проверяем, если в этот день у группы практические занятия, если есть, то не ставим больше лекций в этот день
                            $lectionsToday = Timetable::find()
                                ->asArray()
                                ->select('subjects_id')
                                ->where(['=', 'date', $date])
                                ->andWhere(['=', 'group_id', $groupID])
                                ->all();

                            foreach ($lectionsToday as $lection) {
                                $isPrectice = Subjects::find()
                                    ->asArray()
                                    ->select('practice')
                                    ->where(['=', 'ID', $lection['subjects_id']])
                                    ->one();
                                $isPrectice = $isPrectice['practice'];
                                //если уже есть практика в этот день, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                                //? можно оптимизировать, чтобы сразу переходить на следующий день
                                if ($isPrectice == 1) {
                                    //echo "Нельзя ставить никакие лекции в один день с практикой<br/>";
                                    $lectFilterStatus = 0;
                                }
                            }
                        }

                        //нельзя ставить предмет боьшее число раз в неделю, чем задано в настройках
                        if($lectFilterStatus == 1) {
                            global $inWeek;
                            global $maxInWeek;
                            //проверяем чтобы не ставить лекций этого предмета больше, чем можно максимально в неделю
                            $inWeek = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(subjects_id) AS subjInWeek'])
                                ->where(['<=', 'date', $firstMonday])
                                ->andWhere(['>=', 'date', $firstMonday + 518400])//понедельник + 6 дней
                                ->andWhere(['=', 'group_id', $groupID])
                                ->one();
                            $inWeek = $inWeek['subjInWeek'];

                            $maxInWeek = Subjects::find()
                                ->asArray()
                                ->select('max_week')
                                ->where(['=', 'ID', $subjId])
                                ->one();
                            $maxInWeek = $maxInWeek['max_week'];
                            //если на этой неделе предмета больше или равно макс. кол-ву в неделю, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            if ($inWeek >= $maxInWeek) {
                                //echo "Нельзя ставить предмета больше его максимума в неделю<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить предмет большее число раз, чем его максимальное число, указанное в настройках
                        if($lectFilterStatus == 1) {
                            global $allCurrentSubj;
                            global $maxSubj;
                            //проверяем чтобы не ставить лекций этого предмета больше, чем всего максимально возможно
                            $allCurrentSubj = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(subjects_id) AS subj'])
                                ->where(['=', 'group_id', $groupID])
                                ->one();
                            $allCurrentSubj = $allCurrentSubj['subj'];

                            $maxSubj = Lessons::find()
                                ->asArray()
                                ->select('quantity')
                                ->where(['=', 'course_id', $courseID])
                                ->andWhere(['=', 'subject_id', $subjId])
                                ->one();
                            $maxSubj = $maxSubj['quantity'];

                            //если предмета больше или равно макс. кол-ву, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            if ($allCurrentSubj >= $maxSubj) {
                                //echo "Нельзя ставить предмета больше его общего количества<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить преподавателю пары в разных групах в одно время
                        if($lectFilterStatus == 1) {
                            $lectCount = 0;
                            $lectInOtherGroup = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(teacher_id) AS tId'])
                                ->where(['=', 'teacher_id', $teacherID])
                                ->andWhere(['=', 'x', $x])
                                ->andWhere(['=', 'y', $y])
                                ->one();
                            $lectCount = $lectInOtherGroup['tId'];
                            if($lectCount > 0) {
                                //echo "нельзя ставить преподавателю пары в разніх групах в одно время<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //узнаём lecture_id - id пары из lecture_table
                        //$lecture_id = 1; //пока не высчитываем, возможно стоит убрать эту колонку из таблицы
                        $lecture_id = $this->getLectureId($y, $currentCorpsId); //вычисляем id лекции по её порядковому номеру
                        //$lecture_id = $y; //берём не id пары, а номер

                        //состоялась ли лекция
                        $statusLect = 1; //по умолчанию ставим, что состоялась

                        //наконец-то прошли все проверки и делаем запись в базу
                        //id - автоинкремент, не передаём
                        //corps_id - $currentCorpsId
                        //audience_id - $audienceID
                        //subjects_id - $subjId
                        //teacher_id - $teacherID
                        //group_id - $groupID
                        //lecture_id - $lecture_id id пары из lecture_table
                        //date - $date
                        //status - $status
                        //part_id - $id расписания из timetable_parts
                        //x - $x координата, номер дня п.п.
                        //y - $y координата, номер пары п.п.

                        //если предмет прошёл фильтры, то записываем его в базу
                        if($lectFilterStatus == 1) {
                            $timetable = new Timetable();
                            $timetable->corps_id = $currentCorpsId;
                            $timetable->audience_id = $audienceID;
                            $timetable->subjects_id = $subjId;
                            $timetable->teacher_id = $teacherID;
                            $timetable->group_id = $groupID;
                            $timetable->lecture_id = $lecture_id;
                            $timetable->date = $date;
                            $timetable->status = $statusLect;
                            $timetable->part_id = $id;
                            $timetable->x = $x;
                            $timetable->y = $y;
                            $timetable->save();
                            //echo "Добавили пару<br/>";
                            break;
                        }

                    } //цикл по лекциям

                } //цикл по парам
            } //цикл по дням

        }//цикл по группам
    }

    public function getLectureId($y, $corpsId) {
        $lectures_values = LectureTable::find()->asArray()->select(['ID', 'time_start'])
            ->where(['=', 'corps_id', $corpsId])
            ->orderBy('time_start')
            ->all();

        $lectures_ids = ArrayHelper::getColumn($lectures_values, 'ID');

        $lectureId = $lectures_ids[$y-1];

        return $lectureId;
    }

    public function getTeacherName($id) {
        echo Subjects::getTeacherNameById($id);
    }

    public function getTeacherType($id) {
        echo TeacherMeta::getTeacherType($id);
    }

    public function getTeacherTime($id, $part_id) {
        //максимум часов занятий в месяц
        $inMonth = TeacherMeta::find()->asArray()->select('montshours')->where(['=', 'user_id', $id])->one();
        $inMonth = $inMonth['montshours'];

        $date = strtotime(Date('30.01.2018'));
        //определяем дату первого и последнего дня месяца
        $firstDay = date('01.m.Y', $date);
        $lastDay = date('t.m.Y', $date);
        $firstDay = strtotime($firstDay);
        $lastDay = strtotime($lastDay);

        //echo $firstDay;
        //echo "<br/>";
        //echo $date;
        //echo "<br/>";
        //echo $lastDay;
        //echo "<br/><br/><br/>";

        //всего сгенерированных занятий
        $lectComplete = Timetable::find()
            ->asArray()
            ->select(['COUNT(teacher_id) AS lectCount'])
            //->where(['>=', 'date', $firstDay]) // date >= $firstDay перепроверить через отладчик все условия с подобнфым синтаксисом
            //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
            ->where(['=', 'part_id', $part_id])
            ->andWhere(['=', 'teacher_id', $id])
            ->one();
        //всего сгенерированных часов занятий
        $lectComplete = $lectComplete['lectCount'] * 2;

        /*
        //всего отработанных занятий (кол-во всех занятий с начала месяца по текущею дату)
        $lectWorked = Timetable::find()
            ->asArray()
            ->select(['COUNT(teacher_id) AS lectCount'])
            ->where(['>=', 'date', $firstDay]) // date >= $firstDay
            ->andWhere(['<=', 'date', $date])// date <= $date
            ->andWhere(['=', 'teacher_id', $id])
            ->one();

        print_r($lectWorked);
        echo "<br/>-<br/>";
        */

        //всего отработанных часов занятий
        $lectWorked = $lectWorked['lectCount']*2;

        //$lectGen = $lectComplete - $lectWorked;

        //$lectFree = $inMonth - $lectWorked - $lectGen;

        $lectFree = $inMonth - $lectComplete;

        $hours = array();
        $hours['month'] = $inMonth; //возможно в месяц всего
        $hours['gen'] = $lectComplete; //сгенерированные запланированные занятия
        $hours['free'] = $lectFree; //свободные часы
        //$hours['work'] = $lectWorked; //отработанные занятия

        return $hours;
    }

    public function getListsCount($id)
    {
        $timetableParts = TimetableParts::find()->asArray()->select(['datestart', 'dateend', 'cols'])->where(['=', 'id', $id])->one();

        $days = $timetableParts['cols'];
        $firstDay = $timetableParts['datestart'];
        $lastDay = $timetableParts['dateend'];

        $formatter = new \yii\i18n\Formatter;
        $day = $formatter->asDate($firstDay, "l");
        $firstDay = $formatter->asDate($firstDay, "dd.MM.yyyy");
        $lastDay = $formatter->asDate($lastDay, "dd.MM.yyyy");

        switch ($day) {
            case 'Monday':
                $count = 0;
                break;
            case 'Tuesday':
                $count = 1;
                $days = $days - 6;
                break;
            case 'Wednesday':
                $count = 1;
                $days = $days - 5;
                break;
            case 'Thursday':
                $count = 1;
                $days = $days - 4;
                break;
            case 'Friday':
                $count = 1;
                $days = $days - 3;
                break;
            case 'Saturday':
                $count = 1;
                $days = $days - 2;
                break;
            case 'Sunday':
                $count = 1;
                $days = $days - 1;
                break;
        }

        if ( ($days/7) - intval(($days/7) ) == 0) {
            $count = ($days/7) + $count;
        } else {
            $count = ($days/7) + $count + 1;
        }

        $timetable = array();
        $timetable['count'] = $count;
        $timetable['start'] = $firstDay;
        $timetable['end'] = $lastDay;

        return $timetable;
    }
}
