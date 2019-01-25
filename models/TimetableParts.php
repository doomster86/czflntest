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
            //[['dateend', 'datestart'], 'validateDateend'],
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

        $part = (int)date('mY', $datestart);

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
        $this->mont = $part;
        $this->save();

        //$this->generateLectures($datestart, $dateend, $cols, $rows, $part);
    }

    public function generateLecturesRnps($datestart, $dateend, $cols, $rows, $mont, $gid) {
        $datestart = (int)$datestart;
        $mont = (int)$mont;
        $id = TimetableParts::find()
            ->asArray()
            ->select('id')
            ->where(['=', 'datestart', $datestart])
            ->one();
        $id = $id['id'];
        //группа
        $groups = new Groups();
        $Group = $groups->find()
            ->where(['ID' => $gid])
            ->asArray()
            ->one();
        $groupID = $Group['ID'];
        $Rnp = Rnps::find()
            ->where(['prof_id' => $Group['course']])
            ->asArray()
            ->one();
        $rnpSubjects = RnpSubjects::find()
            ->where(['rnp_id' => $Rnp['ID']])
            ->asArray()
            ->all();

        //обход по дням
        for ($i = 0; $i < $cols; $i++ ) {
            //координата номера дня
            $x = $i + 1;

            //определяем текущею дату
            $date = $datestart + 86400 * $i;

            if ($date < $Group['date_start'] || $Group['date_end'] < $date) {
                continue;
            }

            $date_diff = $date - $Group['date_start'];


            $num_week =  ceil(date('d', $date_diff)/7);

            //echo "день";
            $formatter = new \yii\i18n\Formatter;
            //v($formatter->asDate($date, "dd.MM.yyyy"));

            //определяем дату первого понедельника в этой неделе генерируемого расписания
            $firstMonday = $date;
            $day = $formatter->asDate($firstMonday, "l");
            while ($day != 'Monday') {
                $firstMonday = $firstMonday - 86400;
                $day = $formatter->asDate($firstMonday, "l");
            }

            //echo "Первый понедельник ".$firstMonday."<br/>";

            //обход по парам
            for ($j = 0; $j < $rows; $j++) {
                //пробуем поставить предмет в ячейку, если не подходит, пробуем следующий и т.д.
                //если не можем поставить без окна, то заканчиваем день
                //перебираем субьекты РНП
                $cnt=0;
                foreach ($rnpSubjects as $lesson) {
                    global $lectFilterStatus;
                    $lectFilterStatus = 1; //по умолчанию, считаем что можем поставить лекцию
                    $rnpModules = Modules::find()
                        ->where(['subject_id' => $lesson['ID']])
                        ->asArray()
                        ->all();
                    $subjId = $lesson['ID'];
                    $column_num = 0; // номер столбца, может прогодится
                    $column_plan = 0; // часов в неделю
                    $column_rep = 0; // число недель
                    foreach ($rnpModules as $module) { // перебираем модули РНП
                        $column_rep += $module['column_rep'];
                        if ($num_week <= $column_rep) { // если неделя попадает в РНП, присваиваем часы
                            $column_num = $module['column_num'];
                            $column_plan = $module['column_plan'];
                            break;
                        }
                    }
                    //echo $num_week;
                    if (!$column_plan){ // если уже нет запланированных часов, больше не заполняем
                        $lectFilterStatus = 0;
                    }

                    //echo $lectFilterStatus . '<br />';
                    $teacher = Nakaz::find()->where(['subject_id' => $lesson['ID']])
                        ->asArray()
                        ->orderBy(['column_num' => SORT_DESC])
                        ->one();
                    $teacherID = $teacher['teacher_id'];

                    //узнаём аудиторию лекции
                    $audienceID = RnpSubjects::find()
                        ->asArray()
                        ->select('audience_id')
                        ->where(['ID' => $lesson['ID']])
                        ->one();
                    $audienceID = $audienceID['audience_id'];
                    if (empty($audienceID)) { // если аудитория не задана, нужно поставить в любую другую свободную
                                              // с соблюдением правил (тот же корпус, аудитория свободна)
                        //повременим с этим
                        $audience = Timetable::find()
                            ->asArray()
                            ->where(['=', 'date', $date])
                            ->andWhere(['=', 'group_id', $groupID])
                            ->one();
                        if (!empty($audience)) {
                            $audienceID = $audience['audience_id'];
                        } else {
                            $audience = Audience::find()
                                ->asArray()
                                ->orderBy('rand()')
                                ->one();
                            $audienceID = $audience['ID'];
                        }
                    }
                    //узнаём корпус аудитории
                    $currentCorpsId = Audience::find()
                        ->asArray()
                        ->select('corps_id')
                        ->where(['ID' => $audienceID])
                        ->one();
                    $currentCorpsId = $currentCorpsId['corps_id'];
                    //echo "Корпус";
                    //v($currentCorpsId);
                    //узнаём тип занятия (теоретичне навчання/виробниче навчання/виробнича практика)
                    $type = RnpSubjects::find()
                        ->asArray()
                        ->select('practice')
                        ->where(['ID' => $lesson['ID']])
                        ->one();
                    $type = $type['practice'];
                    //координата номера пары узнаётся через кол-во пар в этот день в этой аудитории
                    $lecturesCounterCorps = Timetable::find()
                        ->asArray()
                        ->select(['COUNT(id) AS counter'])
                        ->where(['=', 'audience_id', $audienceID])
                        ->andWhere(['=', 'date', $date])
                        ->one();
                    $y = $lecturesCounterCorps['counter'];
                    $y = $y+1; //т.е. предполагаем что пара занимает следующею свободную ячейку
                    //echo "Номер пары";
                    //v($y);

                    $half = 2; //по-умолчанию ставим целую пару

                    //далее идёт проверка по группе правил, которые запрещают ставить лекцию в ячейку

                    //нельзя ставить пару, если у корпуса их может быть только $lecturesCounterCorps
                    if($lectFilterStatus == 1) {
                        $lecturesCounterCorps = LectureTable::find()
                            ->asArray()
                            ->select(['COUNT(corps_id) AS corps_id'])
                            ->where(['=', 'corps_id', $currentCorpsId])
                            ->one();
                        $lecturesCounterCorps = $lecturesCounterCorps['corps_id'];
                        if($y > $lecturesCounterCorps) {
                            $err =  "У корпуса ".$lecturesCounterCorps." пар. Нельзя ставить пару ".$y."<br/>";
                            $lectFilterStatus = 0;
                        }
                    }
                    //нельзя ставить первым занятием практику
                    if($lectFilterStatus == 1) {
                        $first = 0;
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
                            if ($type != 0) {
                                $err = "Первый предмет не может быть практикой<br/>";
                                $lectFilterStatus = 0;
                            }
                        }
                    }
                    //нельзя ставить практику студентам вместе с обычными лекциями в один день
                    if($lectFilterStatus == 1) {
                        //если практика
                        if($type != 0) {
                            //узнаём были ли лекции в этот день у группы, если да, то практику нельзя ставить группе
                            $lectInThisDateGroup = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(id) AS lectCount'])
                                ->where(['=', 'date', $date])
                                ->andWhere(['=', 'group_id', $groupID])
                                ->one();
                            $lectInThisDateGroup = $lectInThisDateGroup['lectCount'];

                            if ($lectInThisDateGroup > 0) {
                                $err = "Нельзя ставить практику группе, потому что уже были лекции в этот день<br/>";
                                $lectFilterStatus = 0;
                            }
                        }
                    }


                    //нельзя ставить практику преподавателю вместе с обычными лекциями в один день
                    if($lectFilterStatus == 1) {
                        //если практика
                        if($type != 0) {
                            //узнаём были ли лекции в этот день у преподавателя, если да, то практику нельзя ставить преподавателю
                            $lectInThisDateTeacher = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(id) AS lectCount'])
                                ->where(['=', 'date', $date])
                                ->andWhere(['=', 'teacher_id', $teacherID])
                                ->one();
                            $lectInThisDateTeacher = $lectInThisDateTeacher['lectCount'];

                            if($lectInThisDateTeacher > 0 ) {
                                $err = "Нельзя ставить практику преподавателю, потому что уже были леции в этот день<br/>";
                                $lectFilterStatus = 0;
                            }
                        }
                    }
                    //нельзя ставить студентам занятия в разных корпусах в один день
                    if($lectFilterStatus == 1) {
                        //проверяем, чтобы у группы не было в этот день занятий в разных корпусах
                        $sameCorps = Timetable::find()
                            ->asArray()
                            ->select(['COUNT(id) AS counter'])
                            ->where(['!=', 'corps_id', $currentCorpsId])
                            ->andWhere(['=', 'date', $date])
                            ->andWhere(['=', 'group_id', $groupID])
                            ->one();
                        $sameCorps = $sameCorps['counter'];

                        //если есть занятия в другом корпусе, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                        if ($sameCorps != 0) {
                            $err = "Нельзя ставить группе занятия в разных корпусах в один день<br/>";
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
                            ->one();
                        $sameCorps = $sameCorps['counter'];

                        //если есть занятия в другом корпусе, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                        if ($sameCorps != 0) {
                            $err = "Нельзя ставить преподавателю занятия в разных корпусах в один день<br/>";
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
                            $err = "Преподаватель в этот день не работает<br/>";
                            $lectFilterStatus = 0;
                        }
                    }


                    //нельзя ставить преподавателю больше занятий в неделю, чем позволяет норматив
                    if($lectFilterStatus == 1) {
                        //считаем сколько преподаватель наработал часов на этой неделе,
                        //если больше нормы, то берём следующею лекцию из foreach ($groupLessons as $lesson)

                        //начиная с первого понедельника до воскресенья, считаем количество пар, которые провёл преподаватель
                        //!!! надо будет переписать эту часть для более точного учёта, т.к. сейчас все лекции в этой таблицебудут считаться как состоявшиеся
                        $lectComplete = Timetable::find()
                            ->asArray()
                            ->select(['COUNT(teacher_id) AS lectLeft'])
                            ->where(['>=', 'date', $firstMonday]) // date >= $firstMonday
                            ->andWhere(['<=', 'date', $firstMonday + 518400])//date <= понедельник+6 дней
                            ->andWhere(['=', 'half', 2])
                            ->andWhere(['=', 'mont', $mont])
                            //->groupBy(['teacher_id'])
                            ->one();

                        //кол-во часов, которое преподатель проработал уже, одна пара - два академических часа
                        $lectComplete = $lectComplete['lectLeft'] * 2;

                        $lectCompleteHalf = Timetable::find()
                            ->asArray()
                            ->select(['COUNT(teacher_id) AS lectLeft'])
                            ->where(['>=', 'date', $firstMonday]) // date >= $firstMonday
                            ->andWhere(['<=', 'date', $firstMonday + 518400])//date <= понедельник+6 дней
                            ->andWhere(['=', 'half', 1])
                            ->andWhere(['=', 'mont', $mont])
                            //->groupBy(['teacher_id'])
                            ->one();
                        $lectCompleteHalf = $lectCompleteHalf['lectLeft'];

                        $lectComplete = $lectComplete + $lectCompleteHalf;

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
                        } else {
                            $dif = $lectMax['hours'] - $lectComplete;
                            if($dif == 1) {
                                $half = $dif;
                            }
                        }
                    }

                    //нельзя ставить преподавателю больше занятий в календарный месяц, чем позволяет норматив
                    if($lectFilterStatus == 1) {
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
                            ->where(['=', 'mont', $mont])
                            //->where(['>=', 'date', $firstDay]) // date >= $firstDay
                            //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
                            ->andWhere(['=', 'teacher_id', $teacherID])
                            ->andWhere(['=', 'half', 2])
                            //->groupBy(['teacher_id'])
                            ->one();

                        //кол-во часов, которое преподатель проработал уже, одна пара - два академических часа
                        $lectComplete = $lectComplete['lectCount'] * 2;

                        $lectCompleteHalf = Timetable::find()
                            ->asArray()
                            ->select(['COUNT(teacher_id) AS lectCount'])
                            ->where(['=', 'mont', $mont])
                            //->where(['>=', 'date', $firstDay]) // date >= $firstDay
                            //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
                            ->andWhere(['=', 'teacher_id', $teacherID])
                            ->andWhere(['=', 'half', 1])
                            //->groupBy(['teacher_id'])
                            ->one();
                        $lectCompleteHalf = $lectCompleteHalf['lectCount'];

                        $lectComplete = $lectComplete + $lectCompleteHalf;

                        //максимальное кол-во часов в календарный месяц для преподавателя
                        $lectMax = TeacherMeta::find()
                            ->asArray()
                            ->select('montshours')
                            ->where(['user_id' => $teacherID])
                            ->one();

                        //если больше нормы, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                        if ($lectComplete >= $lectMax['montshours']) {
                            $err = "Преподаватель уже отработал норму в месяц<br/>";
                            $lectFilterStatus = 0;
                        } else {
                            $dif = $lectMax['montshours'] - $lectComplete;
                            if($dif == 1) {
                                $half = $dif;
                            }
                        }
                    }

                    //нельзя ставить студентам одну и ту же пару в один и тот же день несколько раз
                    if($lectFilterStatus == 1) {
                        //проверяем, нет ли такой же пары в этот день у этой же группы, в следующий или предыдущий день в этой же группы
                        //параметры следует отрегулировать, чтобы не было пустых дней, когда уже почти никих пар поставить нельзя
                        $sameLect = Timetable::find()
                            ->asArray()
                            ->select(['COUNT(id) AS sameLect'])
                            ->where(['=', 'date', $date])
                            //->andWhere(['=', 'date', $date - 86400]) ограничение на такую же пару в предыдущий день
                            //->andWhere(['=', 'date', $date + 86400]) ограничение на такую же пару на следующий день
                            ->andWhere(['=', 'group_id', $groupID])
                            ->andWhere(['=', 'subjects_id', $subjId])
                            ->andWhere(['=', 'part_id', $id])
                            ->one();
                        $sameLect = $sameLect['sameLect'];

                        if ($sameLect > 0) {
                            $err = "Нельзя ставить одинаковые пары в один день<br/>";
                            $lectFilterStatus = 0;
                        }
                    }

                    // установить расписания для ДКА
                    if($lectFilterStatus == 1) {
                        $lecture_id = 13;

                        //состоялась ли лекция
                        $statusLect = 1; //по умолчанию ставим, что состоялась

                        if ($date == $Group['date_dka_1']) {
                            if (!$cnt) {
                                $timetable = new Timetable();
                                $timetable->corps_id = $currentCorpsId;
                                $timetable->title = 'ДКА 1';
                                $timetable->audience_id = $audienceID;
                                $timetable->subjects_id = $mont;
                                $timetable->teacher_id = $teacherID;
                                $timetable->group_id = $groupID;
                                $timetable->lecture_id = $lecture_id;
                                $timetable->date = $date;
                                $timetable->status = $statusLect;
                                $timetable->half = $half;
                                $timetable->part_id = $id;
                                $timetable->x = $x;
                                $timetable->y = $y;

                                if ($timetable->validate()) {
                                    $timetable->save();
                                }
                                else {
                                    print_r($timetable->getErrors());
                                }
                            }
                        }
                        if ($date == $Group['date_dka_2']) {
                            if (!$cnt) {
                                $timetable = new Timetable();
                                $timetable->corps_id = $currentCorpsId;
                                $timetable->title = 'ДКА 2';
                                $timetable->audience_id = $audienceID;
                                $timetable->subjects_id = $mont;
                                $timetable->teacher_id = $teacherID;
                                $timetable->group_id = $groupID;
                                $timetable->lecture_id = $lecture_id;
                                $timetable->date = $date;
                                $timetable->status = $statusLect;
                                $timetable->half = $half;
                                $timetable->part_id = $id;
                                $timetable->x = $x;
                                $timetable->y = $y;

                                if ($timetable->validate()) {
                                    $timetable->save();
                                }
                                else {
                                    print_r($timetable->getErrors());
                                }
                            }
                        }
                    }

                    //нельзя ставить больше занятий в неделю, чем позволяет РНП
                    //echo $mont;
                    if($lectFilterStatus == 1) {
                        $firstDay = strtotime("+" . $num_week - 1 . " week", $datestart);
                        $lectComplete = Timetable::find()
                            ->asArray()
                            ->select(['COUNT(teacher_id) AS lectLeft'])
                            ->where(['>=', 'date', $firstDay]) // date >= $firstMonday
                            ->andWhere(['<=', 'date', $firstDay + 518400])//date <= день+6 дней
                            ->andWhere(['=', 'half', 2])
                            ->andWhere(['=', 'subjects_id', $subjId])
                            //->groupBy(['teacher_id'])
                            ->one();
                        $lectComplete = $lectComplete['lectLeft'] * 2;
                        $lectCompleteHalf = Timetable::find()
                            ->asArray()
                            ->select(['COUNT(teacher_id) AS lectLeft'])
                            ->where(['>=', 'date', $firstDay]) // date >= $firstMonday
                            ->andWhere(['<=', 'date', $firstDay + 518400])//date <= понедельник+6 дней
                            ->andWhere(['=', 'half', 1])
                            ->andWhere(['=', 'subjects_id', $subjId])
                            //->groupBy(['teacher_id'])
                            ->one();
                        $lectCompleteHalf = $lectCompleteHalf['lectLeft'];

                        $lectComplete = $lectComplete + $lectCompleteHalf;

                        if ($lectComplete >= $column_plan) {
                            //echo "Преподаватель уже отработал норму в неделю<br/>";
                            $lectFilterStatus = 0;
                        } else {
                            $dif = $column_plan - $lectComplete;
                            if($dif == 1) {
                                $half = $dif;
                            }
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
                            ->andWhere(['=', 'part_id', $id])
                            ->all();

                        foreach ($lectionsToday as $lection) {
                            $isPrectice = RnpSubjects::find()
                                ->asArray()
                                ->select('practice')
                                ->where(['=', 'ID', $lection['subjects_id']])
                                ->one();
                            $isPrectice = $isPrectice['practice'];
                            //если уже есть практика в этот день, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            //? можно оптимизировать, чтобы сразу переходить на следующий день
                            if ($isPrectice != 0) {
                                $err = "Нельзя ставить никакие лекции в один день с практикой<br/>";
                                $lectFilterStatus = 0;
                            }
                        }
                        if ($type == 1) {
                            for ($k = 2; $k <= 4; $k++) {
                                $timetable = new Timetable();
                                $timetable->corps_id = $currentCorpsId;
                                $timetable->title = $lesson['title'];
                                $timetable->audience_id = $audienceID;
                                $timetable->subjects_id = $subjId;
                                $timetable->teacher_id = $teacherID;
                                $timetable->group_id = $groupID;
                                $timetable->lecture_id = $lecture_id;
                                $timetable->date = $date;
                                $timetable->status = $statusLect;
                                $timetable->half = $half;
                                $timetable->part_id = $id;
                                $timetable->x = $x;
                                $timetable->y = $k;

                                //чтобы снова начать перебор с начала всех пар
                                $j = 0;
                                if ($timetable->validate()) {
                                    $timetable->save();
                                }
                                else {
                                    print_r($timetable->getErrors());
                                }
                            }
                        }
                    }

                    // нельзя ставить группе навчання, если уже произошла ДКА
                    if($lectFilterStatus == 1) {
                        if ($date >= $Group['date_dka_1']) {
                            if ($type < 2) {
                                $lectFilterStatus = 0;
                            }
                        }
                    }
                    // нельзя ставить группе практику, пока не произошла ДКА
                    if($lectFilterStatus == 1) {
                        if ($date <= $Group['date_dka_1']) {
                            if ($type == 2) {
                                $lectFilterStatus = 0;
                            }
                        }
                    }
                    $cnt++;
                    $lecture_id = 13;


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
                        if($currentCorpsId != NULL && $audienceID != NULL && $subjId != NULL &&
                            $teacherID != NULL && $groupID != NULL && $lecture_id != NULL && $date != NULL &&
                            $statusLect != NULL && $half != NULL && $id != NULL && $x != NULL && $y != NULL) {

                            $timetable = new Timetable();
                            $timetable->corps_id = $currentCorpsId;
                            $timetable->title = $lesson['title'];
                            $timetable->audience_id = $audienceID;
                            $timetable->subjects_id = $subjId;
                            $timetable->teacher_id = $teacherID;
                            $timetable->group_id = $groupID;
                            $timetable->lecture_id = $lecture_id;
                            $timetable->date = $date;
                            $timetable->status = $statusLect;
                            $timetable->half = $half;
                            $timetable->part_id = $id;
                            $timetable->x = $x;
                            $timetable->y = $y;

                            //чтобы снова начать перебор с начала всех пар
                            $j = 0;
                            if ($timetable->validate()) {
                                $timetable->save();
                            }
                            else {
                                print_r($timetable->getErrors());
                            }
                            //echo "Добавили пару<br/>";
                        }
                        break;
                    } else {
                        $errLog =  "<br/>Корпус: ".$currentCorpsId;
                        $errLog .= "<br/> Аудитория: ".$audienceID;
                        $errLog .= "<br/> Предмет: ".$subjId;
                        $errLog .= "<br/> Преподаватель: ".$teacherID;
                        $errLog .= "<br/> Группа: ".$groupID;
                        $errLog .= "<br/> x: ".$x;
                        $errLog .= "<br/> y: ".$y;
                        $errLog .= "<br/>".$err;
                        //echo $errLog;
                    }

                } //цикл по лекциям
            } //цикл по парам
        } //цикл по дням
    }

        public function generateLectures($datestart, $dateend, $cols, $rows, $mont, $gid) {
        $datestart = (int)$datestart;
        $dateend = (int)$dateend;
        $mont = (int)$mont;

        $id = TimetableParts::find()
            ->asArray()
            ->select('id')
            ->where(['=', 'datestart', $datestart])
            ->one();
        $id = $id['id'];

        //все группы
        $groups = new Groups();
        $allGroups = $groups->find()
            ->where(['ID' => $gid])
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
                /// будем считать. в какой модуль попадаем в rnp


                //координата номера дня
                $x = $i + 1;

                //определяем текущею дату
                $date = $datestart + 86400 * $i;

                //echo "день";
                $formatter = new \yii\i18n\Formatter;
                //v($formatter->asDate($date, "dd.MM.yyyy"));

                //определяем дату первого понедельника в этой неделе генерируемого расписания
                $firstMonday = $date;
                $day = $formatter->asDate($firstMonday, "l");
                while ($day != 'Monday') {
                    $firstMonday = $firstMonday - 86400;
                    $day = $formatter->asDate($firstMonday, "l");
                }

                //echo "Первый понедельник ".$firstMonday."<br/>";

                //обход по парам
                for($j = 0; $j < $rows; $j++) {
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
                        //echo "Преподаватель";
                        //v($teacherID);

                        //узнаём аудиторию лекции
                        $audienceID = Subjects::find()
                            ->asArray()
                            ->select('audience_id')
                            ->where(['ID' => $subjId])
                            ->one();
                        $audienceID = $audienceID['audience_id'];
                        //echo "Аудитория";
                        //v($audienceID);

                        //узнаём корпус аудитории
                        $currentCorpsId = Audience::find()
                            ->asArray()
                            ->select('corps_id')
                            ->where(['ID' => $audienceID])
                            ->one();
                        $currentCorpsId = $currentCorpsId['corps_id'];
                        //echo "Корпус";
                        //v($currentCorpsId);

                        //узнаём тип занятия (практика\не практика)
                        $type = Subjects::find()
                            ->asArray()
                            ->select('practice')
                            ->where(['ID' => $subjId])
                            ->one();
                        $type = $type['practice'];
                        //echo "Тип занятия";
                        //v($type);

                        //координата номера пары узнаётся через кол-во пар в этот день в этой аудитории
                        $lecturesCounterCorps = Timetable::find()
                            ->asArray()
                            ->select(['COUNT(id) AS counter'])
                            ->where(['=', 'audience_id', $audienceID])
                            ->andWhere(['=', 'date', $date])
                            ->one();
                        $y = $lecturesCounterCorps['counter'];
                        $y = $y+1; //т.е. предполагаем что пара занимает следующею свободную ячейку
                        //echo "Номер пары";
                        //v($y);

                        $half = 2; //по-умолчанию ставим целую пару

                        //далее идёт проверка по группе правил, которые запрещают ставить лекцию в ячейку

                        //нельзя ставить пару, если у корпуса их может быть только $lecturesCounterCorps
                        if($lectFilterStatus == 1) {
                            $lecturesCounterCorps = LectureTable::find()
                                ->asArray()
                                ->select(['COUNT(corps_id) AS corps_id'])
                                ->where(['=', 'corps_id', $currentCorpsId])
                                ->one();
                            $lecturesCounterCorps = $lecturesCounterCorps['corps_id'];
                            if($y > $lecturesCounterCorps) {
                                $err =  "У корпуса ".$lecturesCounterCorps." пар. Нельзя ставить пару ".$y."<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить первым занятием практику
                        if($lectFilterStatus == 1) {
                            $first = 0;
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
                                    $err = "Первый предмет не может быть практикой<br/>";
                                    $lectFilterStatus = 0;
                                }
                            }
                        }

                        //нельзя ставить практику студентам вместе с обычными лекциями в один день
                        if($lectFilterStatus == 1) {
                            //если практика
                            if($type == 1) {
                                //узнаём были ли лекции в этот день у группы, если да, то практику нельзя ставить группе
                                $lectInThisDateGroup = Timetable::find()
                                    ->asArray()
                                    ->select(['COUNT(id) AS lectCount'])
                                    ->where(['=', 'date', $date])
                                    ->andWhere(['=', 'group_id', $groupID])
                                    ->one();
                                $lectInThisDateGroup = $lectInThisDateGroup['lectCount'];

                                if ($lectInThisDateGroup > 0) {
                                    $err = "Нельзя ставить практику группе, потому что уже были лекции в этот день<br/>";
                                    $lectFilterStatus = 0;
                                }
                            }
                        }

                        //нельзя ставить практику преподавателю вместе с обычными лекциями в один день
                        if($lectFilterStatus == 1) {
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
                                    $err = "Нельзя ставить практику преподавателю, потому что уже были леции в этот день<br/>";
                                    $lectFilterStatus = 0;
                                }
                            }
                        }

                        //нельзя ставить студентам занятия в разных корпусах в один день
                        if($lectFilterStatus == 1) {
                            //проверяем, чтобы у группы не было в этот день занятий в разных корпусах
                            $sameCorps = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(id) AS counter'])
                                ->where(['!=', 'corps_id', $currentCorpsId])
                                ->andWhere(['=', 'date', $date])
                                ->andWhere(['=', 'group_id', $groupID])
                                ->one();
                            $sameCorps = $sameCorps['counter'];

                            //если есть занятия в другом корпусе, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            if ($sameCorps != 0) {
                                $err = "Нельзя ставить группе занятия в разных корпусах в один день<br/>";
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
                                ->one();
                            $sameCorps = $sameCorps['counter'];

                            //если есть занятия в другом корпусе, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            if ($sameCorps != 0) {
                                $err = "Нельзя ставить преподавателю занятия в разных корпусах в один день<br/>";
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
                                $err = "Преподаватель в этот день не работает<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить преподавателю больше занятий в неделю, чем позволяет норматив
                        if($lectFilterStatus == 1) {
                            //считаем сколько преподаватель наработал часов на этой неделе,
                            //если больше нормы, то берём следующею лекцию из foreach ($groupLessons as $lesson)

                            //начиная с первого понедельника до воскресенья, считаем количество пар, которые провёл преподаватель
                            //!!! надо будет переписать эту часть для более точного учёта, т.к. сейчас все лекции в этой таблицебудут считаться как состоявшиеся
                            $lectComplete = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(teacher_id) AS lectLeft'])
                                ->where(['>=', 'date', $firstMonday]) // date >= $firstMonday
                                ->andWhere(['<=', 'date', $firstMonday + 518400])//date <= понедельник+6 дней
                                ->andWhere(['=', 'half', 2])
                                ->andWhere(['=', 'mont', $mont])
                                //->groupBy(['teacher_id'])
                                ->one();

                            //кол-во часов, которое преподатель проработал уже, одна пара - два академических часа
                            $lectComplete = $lectComplete['lectLeft'] * 2;

                            $lectCompleteHalf = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(teacher_id) AS lectLeft'])
                                ->where(['>=', 'date', $firstMonday]) // date >= $firstMonday
                                ->andWhere(['<=', 'date', $firstMonday + 518400])//date <= понедельник+6 дней
                                ->andWhere(['=', 'half', 1])
                                ->andWhere(['=', 'mont', $mont])
                                //->groupBy(['teacher_id'])
                                ->one();
                            $lectCompleteHalf = $lectCompleteHalf['lectLeft'];

                            $lectComplete = $lectComplete + $lectCompleteHalf;

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
                            } else {
                                $dif = $lectMax['hours'] - $lectComplete;
                                if($dif == 1) {
                                    $half = $dif;
                                }
                            }
                        }

                        //нельзя ставить преподавателю больше занятий в календарный месяц, чем позволяет норматив
                        if($lectFilterStatus == 1) {
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
                                ->where(['=', 'mont', $mont])
                                //->where(['>=', 'date', $firstDay]) // date >= $firstDay
                                //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
                                ->andWhere(['=', 'teacher_id', $teacherID])
                                ->andWhere(['=', 'half', 2])
                                //->groupBy(['teacher_id'])
                                ->one();

                            //кол-во часов, которое преподатель проработал уже, одна пара - два академических часа
                            $lectComplete = $lectComplete['lectCount'] * 2;

                            $lectCompleteHalf = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(teacher_id) AS lectCount'])
                                ->where(['=', 'mont', $mont])
                                //->where(['>=', 'date', $firstDay]) // date >= $firstDay
                                //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
                                ->andWhere(['=', 'teacher_id', $teacherID])
                                ->andWhere(['=', 'half', 1])
                                //->groupBy(['teacher_id'])
                                ->one();
                            $lectCompleteHalf = $lectCompleteHalf['lectCount'];

                            $lectComplete = $lectComplete + $lectCompleteHalf;

                            //максимальное кол-во часов в календарный месяц для преподавателя
                            $lectMax = TeacherMeta::find()
                                ->asArray()
                                ->select('montshours')
                                ->where(['user_id' => $teacherID])
                                ->one();

                            //если больше нормы, то берём следующею лекцию из foreach ($groupLessons as $lesson)
                            if ($lectComplete >= $lectMax['montshours']) {
                                $err = "Преподаватель уже отработал норму в месяц<br/>";
                                $lectFilterStatus = 0;
                            } else {
                                $dif = $lectMax['montshours'] - $lectComplete;
                                if($dif == 1) {
                                    $half = $dif;
                                }
                            }
                        }

                        //нельзя ставить студентам одну и ту же пару в один и тот же день несколько раз
                        if($lectFilterStatus == 1) {
                            //проверяем, нет ли такой же пары в этот день у этой же группы, в следующий или предыдущий день в этой же группы
                            //параметры следует отрегулировать, чтобы не было пустых дней, когда уже почти никих пар поставить нельзя
                            $sameLect = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(id) AS sameLect'])
                                ->where(['=', 'date', $date])
                                //->andWhere(['=', 'date', $date - 86400]) ограничение на такую же пару в предыдущий день
                                //->andWhere(['=', 'date', $date + 86400]) ограничение на такую же пару на следующий день
                                ->andWhere(['=', 'group_id', $groupID])
                                ->andWhere(['=', 'subjects_id', $subjId])
                                ->andWhere(['=', 'part_id', $id])
                                ->one();
                            $sameLect = $sameLect['sameLect'];

                            if ($sameLect > 0) {
                                $err = "Нельзя ставить одинаковые пары в один день<br/>";
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
                                ->andWhere(['=', 'part_id', $id])
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
                                    $err = "Нельзя ставить никакие лекции в один день с практикой<br/>";
                                    $lectFilterStatus = 0;
                                }
                            }
                        }

                        //нельзя ставить предмет боьшее число раз в неделю, чем задано в настройках
                        if($lectFilterStatus == 1) {
                            //проверяем чтобы не ставить лекций этого предмета больше, чем можно максимально в неделю

                            //echo "первый понедельние ".$firstMonday."<br/>";
                            $inWeek = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(subjects_id) AS subjInWeek'])
                                ->where(['>=', 'date', $firstMonday])
                                ->andWhere(['<=', 'date', $firstMonday + 518400])//понедельник + 6 дней
                                ->andWhere(['=', 'group_id', $groupID])
                                ->andWhere(['=', 'subjects_id', $subjId])
                                ->andWhere(['=', 'mont', $mont])
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
                                $err = "Нельзя ставить предмета больше его максимума в неделю<br/>";
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
                                ->andWhere(['=', 'mont', $mont])
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
                                $err = "Нельзя ставить предмета больше его общего количества<br/>";
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
                                ->andWhere(['=', 'part_id', $id])
                                ->one();
                            $lectCount = $lectInOtherGroup['tId'];
                            if($lectCount > 0) {
                                $err = "нельзя ставить преподавателю пары в разных групах в одно время<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //нельзя ставить группе пары у разных преподавателей в одно время
                        if($lectFilterStatus == 1) {
                            $lectCount = 0;
                            $lectInOtherTeacher = Timetable::find()
                                ->asArray()
                                ->select(['COUNT(group_id) AS gId'])
                                ->where(['=', 'group_id', $groupID])
                                ->andWhere(['=', 'x', $x])
                                ->andWhere(['=', 'y', $y])
                                ->andWhere(['=', 'part_id', $id])
                                ->one();
                            $lectCount = $lectInOtherTeacher['gId'];
                            if($lectCount > 0) {
                                $err = "нельзя ставить группе пары у разных преподавателей в одно время<br/>";
                                $lectFilterStatus = 0;
                            }
                        }

                        //узнаём lecture_id - id пары из lecture_table
                        $lectureIDs = LectureTable::find()
                            ->asArray()
                            ->select(["ID"])
                            ->where(['=', 'corps_id', $currentCorpsId])
                            ->orderBy('time_start')
                            ->all();
                        $lectureIDs = ArrayHelper::getColumn($lectureIDs, 'ID');
                        //$lecture_id = $lectureIDs[$y];
                        $lecture_id = 13;


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
                            if($currentCorpsId != NULL && $audienceID != NULL && $subjId != NULL &&
                                $teacherID != NULL && $groupID != NULL && $lecture_id != NULL && $date != NULL &&
                                $statusLect != NULL && $half != NULL && $id != NULL && $x != NULL && $y != NULL) {

                                $timetable = new Timetable();
                                $timetable->corps_id = $currentCorpsId;
                                $timetable->audience_id = $audienceID;
                                $timetable->subjects_id = $subjId;
                                $timetable->teacher_id = $teacherID;
                                $timetable->group_id = $groupID;
                                $timetable->lecture_id = $lecture_id;
                                $timetable->date = $date;
                                $timetable->status = $statusLect;
                                $timetable->half = $half;
                                $timetable->part_id = $id;
                                $timetable->x = $x;
                                $timetable->y = $y;

                                //чтобы снова начать перебор с начала всех пар
                                $j = 0;
                                $timetable->save();
                                //echo "Добавили пару<br/>";
                            }
                            break;
                        } else {
                            $errLog =  "<br/>Корпус: ".$currentCorpsId;
                            $errLog .= "<br/> Аудитория: ".$audienceID;
                            $errLog .= "<br/> Предмет: ".$subjId;
                            $errLog .= "<br/> Преподаватель: ".$teacherID;
                            $errLog .= "<br/> Группа: ".$groupID;
                            $errLog .= "<br/> x: ".$x;
                            $errLog .= "<br/> y: ".$y;
                            $errLog .= "<br/>".$err;
                            //echo $errLog;
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

        $mont = Timetable::find()->asArray()->select(['mont'])
            ->where(['=', 'part_id', $part_id])
            ->one();
        $mont = $mont['mont'];

        //всего сгенерированных занятий
        $lectComplete = Timetable::find()
            ->asArray()
            ->select(['COUNT(teacher_id) AS lectCount'])
            //->where(['>=', 'date', $firstDay]) // date >= $firstDay перепроверить через отладчик все условия с подобнфым синтаксисом
            //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
            ->where(['=', 'mont', $mont])
            ->andWhere(['=', 'teacher_id', $id])
            ->andWhere(['=', 'half', 2])
            ->one();
        //всего сгенерированных часов занятий
        $lectComplete = $lectComplete['lectCount'] * 2;

        $lectCompleteHalf = Timetable::find()
            ->asArray()
            ->select(['COUNT(teacher_id) AS lectCount'])
            //->where(['>=', 'date', $firstDay]) // date >= $firstDay перепроверить через отладчик все условия с подобнфым синтаксисом
            //->andWhere(['<=', 'date', $lastDay])// date <= $lastDay
            ->where(['=', 'mont', $mont])
            ->andWhere(['=', 'teacher_id', $id])
            ->andWhere(['=', 'half', 1])
            ->one();
        $lectCompleteHalf = $lectCompleteHalf['lectCount'];

        $lectComplete = $lectComplete + $lectCompleteHalf;

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
        //$lectWorked = $lectWorked['lectCount']*2;

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
        //$firstDay = $formatter->asDate($firstDay, "dd.MM.yyyy");
        //$lastDay = $formatter->asDate($lastDay, "dd.MM.yyyy");

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

    public function getGroupNames() {

        $group_values = Groups::find()->asArray()->select(['ID', "name"])
            ->orderBy('name')
            ->all();
        $group_names = ArrayHelper::getColumn($group_values, 'name');
        $group_ids = ArrayHelper::getColumn($group_values, 'ID');

        $groups = array_combine($group_ids, $group_names);

        return $groups;
    }
}
