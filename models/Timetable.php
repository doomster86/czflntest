<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\RnpSubjects;
use yii\i18n\Formatter;
/**
 * This is the model class for table "timetable".
 *
 * @property integer $id
 * @property integer $corps_id
 * @property integer $audience_id
 * @property integer $subjects_id
 * @property integer $teacher_id
 * @property integer $group_id
 * @property integer $lecture_id
 * @property string $date
 * @property integer $status
 *
 * @property Corps $corps
 * @property Audience $audience
 * @property Subjects $subjects
 * @property User $teacher
 * @property Groups $group
 * @property LectureTable $lecture
 */
class Timetable extends \yii\db\ActiveRecord
{

    public $datestart;
    public $dateend;
    public $length = 2; //максимальная длительность занятия

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'timetable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['corps_id', 'audience_id', 'subjects_id', 'group_id', 'status','part_id', 'x', 'y', 'date', 'half'], 'required'],
            [['corps_id', 'audience_id', 'subjects_id', 'teacher_id', 'group_id', 'lecture_id', 'status', 'half', 'part_id', 'x', 'y', 'date'], 'integer'],
            [['corps_id'], 'exist', 'skipOnError' => true, 'targetClass' => Corps::className(), 'targetAttribute' => ['corps_id' => 'ID']],
            [['audience_id'], 'exist', 'skipOnError' => true, 'targetClass' => Audience::className(), 'targetAttribute' => ['audience_id' => 'ID']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['teacher_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['group_id' => 'ID']],
            [['lecture_id'], 'exist', 'skipOnError' => true, 'targetClass' => LectureTable::className(), 'targetAttribute' => ['lecture_id' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'corps_id' => 'Corps ID',
            'audience_id' => 'Audience ID',
            'subjects_id' => 'Subjects ID',
            'teacher_id' => 'Teacher ID',
            'group_id' => 'Group ID',
            'lecture_id' => 'Lecture ID',
            'date' => 'Date',
            'status' => 'Status',
            'part_id' => 'Part ID',
            'x' => 'X',
            'y' => 'y',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCorps()
    {
        return $this->hasOne(Corps::className(), ['ID' => 'corps_id']);
    }

    public function getAudience()
    {
        return $this->hasOne(Audience::className(), ['ID' => 'audience_id']);
    }

    public function getSubjects()
    {
        return $this->hasOne(Subjects::className(), ['ID' => 'subjects_id']);
    }

    public function getTeacher()
    {
        return $this->hasOne(User::className(), ['id' => 'teacher_id']);
    }

    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['ID' => 'group_id']);
    }

    public function getLength() {
        for($i = 1; $i <= $this->length; $i++) {
            $length[$i] = $i;
        }

        return $length;
    }

    public function getLecture()
    {
        return $this->hasOne(LectureTable::className(), ['ID' => 'lecture_id']);
    }

    public function getPartId()
    {
        return $this->hasOne(LectureTable::className(), ['id' => 'part_id']);
    }

    public function renderPrintTable($datestart, $dateend, $groupID) {
        $output = '';
        if ($groupID) {
            $input_array = Timetable::find()
                ->asArray()
                ->where( [ '>=', 'date', $datestart ] )
                ->andWhere( [ '<=', 'date', $dateend ] )
                ->andWhere( [ '=', 'group_id', $groupID ] )
                ->all();
        } else {
            $input_array = Timetable::find()
                ->asArray()
                ->where( [ '>=', 'date', $datestart ] )
                ->andWhere( [ '<=', 'date', $dateend ] )
                ->all();
        }
        $datediff = floor(($dateend - $datestart) / (60 * 60 * 24));
        if (!empty($input_array)){

        $output .= '<table class="table" id="printtable">';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<th scope="col">Дата<br/>Дні</th>';
        $output .= '<th scope="col">Час</th>';
        $output .= '<th scope="col">Назва дисципліни</th>';
        $output .= '<th scope="col">№<br/>Аудит.</th>';
        $output .= '<th scope="col">Прізвище та ініціали<br/>виклдачів</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        for ($i = 1; $i <= $datediff; $i++) {
            $date = $datestart + ($i * 60 * 60 * 24) - 86400;
            $day = 0;
            $j = 0;
            foreach ($input_array as $value) {
                $td = 0;
                if ($date == $value['date']) {
                    if ($day != $date) {
                        $td = 1;
                    }
                    $day = $date;
                    $teacher = User::find()
                        ->asArray()
                        ->select('id, firstname, middlename, lastname')
                        ->where(['=', 'id', $value['teacher_id']])
                        ->one();
                    $teacherName = $teacher['firstname'] . " " . $teacher['middlename'] ." " . $teacher['lastname'];

                    $audienceNum = Audience::find()
                        ->asArray()
                        ->select('corps_id, num')
                        ->where(['=', 'ID', $value['audience_id']])
                        ->one();
                    $corpsID = $audienceNum['corps_id'];
                    $audienceNum = $audienceNum['num'];

                    $corpsTimes = LectureTable::find()
                        ->asArray()
                        ->select('time_start, time_stop')
                        ->where(['=', 'corps_id', $corpsID])
                        ->orderBy('time_start')
                        ->all();

                    $subjName = RnpSubjects::find()
                        ->asArray()
                        ->select('title')
                        ->where(['=', 'id', $value['subjects_id']])
                        ->one();
                    $subjName = $subjName['title'];
                    if ($td) {
                        $output .= '<tr style="border-top: 3px #000 solid;">';
                    } else {
                        $output .= '<tr>';
                    }
                    $formatter = new Formatter();
                    $format_date = $formatter->asDate($date, "dd.MM.yy");
                    $output .= '<td>'. $format_date .'</td>';
                    if(!empty($corpsTimes))
                    {
                        $time_start = $corpsTimes[$value['y']-1]['time_start'];
                        $time_stop = $corpsTimes[$value['y']-1]['time_stop'];
                        if ($value['half'] == 1){
                            $arr_time_start = array_map('intval', explode(':', $time_start));
                            $time_1 = mktime($arr_time_start[0], $arr_time_start[1], 1, date('m'), date('d'), date('Y'));
                            $arr_time_stop = array_map('intval', explode(':', $time_stop));
                            $time_2 = mktime($arr_time_stop[0], $arr_time_stop[1], 1, date('m'), date('d'), date('Y'));
                            $time_diff = ($time_2 - $time_1)/2;
                            if ($j==0) {
                                $time_stop = date('H:i',$time_2 - $time_diff);
                            } else {
                                $time_start = date('H:i',$time_2 - $time_diff);
                            }
                            $j++;
                        }

                        $output .= '<td>'. $time_start .' - <br />';
                        $output .= $time_stop .'</td>';
                    } else {
                        $output .= '<td></td>';
                    }
                    $output .= '<td>'. $subjName .'</td>';
                    $output .= '<td>'. $audienceNum .'</td>';
                    $output .= '<td><p class="teachername">'. $teacherName .'</p><p class="teachername-append" style="display: none;"><textarea></textarea><br/><button class="btn teachername-save" type="button">Зберегти</button></p></td>';
                    $output .= '</tr>';
                }
            }
        }
        $output .= '</tbody>';
        $output .= '</table>';
    }
        return $output;
    }

    public function renderPrintExel($datestart, $dateend, $groupID) {
        $output = '';
        if ($groupID) {
            $input_array = Timetable::find()
                ->asArray()
                ->where( [ '>=', 'date', $datestart ] )
                ->andWhere( [ '<=', 'date', $dateend ] )
                ->andWhere( [ '=', 'group_id', $groupID ] )
                ->all();
        } else {
            $input_array = Timetable::find()
                ->asArray()
                ->where( [ '>=', 'date', $datestart ] )
                ->andWhere( [ '<=', 'date', $dateend ] )
                ->all();
        }
        $datediff = floor(($dateend - $datestart) / (60 * 60 * 24));
        if (!empty($input_array)){
            $output .= '<table id="tableExcel" class="hidden">';
            $output .= '<thead>';
            $output .= '<tr>';
            $output .= '<th scope="col">група</th>';
            $output .= '<th scope="col">професія';
            $output .= '<th scope="col">дата</th>';
            $output .= '<th scope="col">час</th>';
            $output .= '<th scope="col">предмет</th>';
            $output .= '<th scope="col">ауд</th>';
            $output .= '<th scope="col">викладач</th>';
            $output .= '</tr>';
            $output .= '</thead>';
            $output .= '<tbody>';
            for ($i = 1; $i <= $datediff; $i++) {
                $date = $datestart + ($i * 60 * 60 * 24) - 86400;
                $day = 0;
                $j = 0;
                foreach ($input_array as $value) {
                    $td = 0;
                    if ($date == $value['date']) {
                        if ($day != $date) {
                            $td = 1;
                        }
                        $day = $date;
                        $teacher = User::find()
                            ->asArray()
                            ->select('id, firstname, middlename, lastname')
                            ->where(['=', 'id', $value['teacher_id']])
                            ->one();
                        $teacherName = $teacher['firstname'] . " " . $teacher['middlename'] ." " . $teacher['lastname'];

                        $audienceNum = Audience::find()
                            ->asArray()
                            ->select('corps_id, num')
                            ->where(['=', 'ID', $value['audience_id']])
                            ->one();
                        $corpsID = $audienceNum['corps_id'];
                        $audienceNum = $audienceNum['num'];

                        $corpsTimes = LectureTable::find()
                            ->asArray()
                            ->select('time_start, time_stop')
                            ->where(['=', 'corps_id', $corpsID])
                            ->orderBy('time_start')
                            ->all();

                        $subjName = RnpSubjects::find()
                            ->asArray()
                            ->select('title')
                            ->where(['=', 'id', $value['subjects_id']])
                            ->one();
                        $subjName = $subjName['title'];
                        $groupName = Groups::find()
                            ->asArray()
                            ->select('name, course')
                            ->where(['=', 'ID', $value['group_id']])
                            ->one();
                        $courseName = Courses::find()
                            ->asArray()
                            ->select('name')
                            ->where(['=', 'ID', $groupName['course']])
                            ->one();
                            $output .= '<tr>';
                        $formatter = new Formatter();
                        $format_date = $formatter->asDate($date, "dd.MM.yy");
                        $output .= '<td>'. $groupName['name'] .'</td>';
                        $output .= '<td>'. $courseName['name'] .'</td>';
                        $output .= '<td>'. $format_date .'</td>';
                        if(!empty($corpsTimes))
                        {
                            $time_start = $corpsTimes[$value['y']-1]['time_start'];
                            $time_stop = $corpsTimes[$value['y']-1]['time_stop'];
                            if ($value['half'] == 1){
                                $arr_time_start = array_map('intval', explode(':', $time_start));
                                $time_1 = mktime($arr_time_start[0], $arr_time_start[1], 1, date('m'), date('d'), date('Y'));
                                $arr_time_stop = array_map('intval', explode(':', $time_stop));
                                $time_2 = mktime($arr_time_stop[0], $arr_time_stop[1], 1, date('m'), date('d'), date('Y'));
                                $time_diff = ($time_2 - $time_1)/2;
                                if ($j==0) {
                                    $time_stop = date('H:i',$time_2 - $time_diff);
                                } else {
                                    $time_start = date('H:i',$time_2 - $time_diff);
                                }
                                $j++;
                            }

                            $output .= '<td>'. $time_start .' - ';
                            $output .= $time_stop .'</td>';
                        } else {
                            $output .= '<td></td>';
                        }
                        $output .= '<td>'. $subjName .'</td>';
                        $output .= '<td>'. $audienceNum .'</td>';
                        $output .= '<td><p class="teachernameExel">'. $teacherName .'</p></td>';
                        $output .= '</tr>';
                    }
                }
            }
            $output .= '</tbody>';
            $output .= '</table>';
        }
        return $output;
    }

    public function renderTable($id, $teacherID, $groupID) {
        $output = '';

	    if ( $teacherID && $groupID ) {
		    $input_array = Timetable::find()
		                            ->asArray()
		                            ->where( [ '=', 'part_id', $id ] )
		                            ->andWhere( [ '=', 'teacher_id', $teacherID ] )
		                            ->andWhere( [ '=', 'group_id', $groupID ] )
		                            ->all();
	    }
	    if( $teacherID && !$groupID) {
		    $input_array = Timetable::find()
		                            ->asArray()
		                            ->where( [ '=', 'part_id', $id ] )
		                            ->andWhere( [ '=', 'teacher_id', $teacherID ] )
		                            ->all();
	    }
	    if( $groupID && !$teacherID) {
		    $input_array = Timetable::find()
		                            ->asArray()
		                            ->where( [ '=', 'part_id', $id ] )
		                            ->andWhere( [ '=', 'group_id', $groupID ] )
		                            ->all();
	    }
	    if( !$teacherID && !$groupID ) {
		    $input_array = Timetable::find()
		                            ->asArray()
		                            ->where( [ '=', 'part_id', $id ] )
		                            ->all();
	    }
        /*
        приходит массив вида
               array(9) {
                  [0]=>
                  array(12) {
                    ["id"]=>
                    string(2) "45"
                    ["corps_id"]=>
                    string(1) "4"
                    ["audience_id"]=>
                    string(1) "7"
                    ["subjects_id"]=>
                    string(2) "22"
                    ["teacher_id"]=>
                    string(1) "4"
                    ["group_id"]=>
                    string(1) "1"
                    ["lecture_id"]=>
                    string(1) "1"
                    ["date"]=>
                    string(10) "1514149200"
                    ["status"]=>
                    string(1) "1"
                    ["part_id"]=>
                    string(2) "48"
                    ["x"]=>
                    string(1) "1"
                    ["y"]=>
                    string(1) "1"
                  }
        */

        $timetable = new TimetableParts();

        $date_array = $timetable
            ->find()
            ->asArray()
            ->where(['id' => $id])
            ->all();

        $date_array = $date_array[0];
        $datestart = $date_array['datestart'];
        $datestart = (int)$datestart;
        $dateend = $date_array['dateend'];
        $dateend = (int)$dateend;
        $cols_num = $date_array['cols']; //кол-во дней
        $rows_num = $date_array['rows'];

        foreach ($input_array as &$value) {
            $value['date'] = (strtotime( $value['date']) - $datestart) / 86400 + 1;
        }

        $formatter = new \yii\i18n\Formatter;

        $output .= '<h2>Розклад з ' . $formatter->asDate($datestart, "dd.MM.yyyy") . ' по ' . $formatter->asDate($dateend, "dd.MM.yyyy") . '</h2>';

        if($groupID) {
            $output .= Html::a('Роздрукувати розклад групи', ['timetable/print', 'table_id' => $id, 'group_id' => $groupID], ['class' => 'btn btn-success btn-right']);
        }

        if($teacherID) {
            $output .= Html::a('Роздрукувати розклад викладача', ['timetable/print', 'table_id' => $id, 'teacher_id' => $teacherID], ['class' => 'btn btn-success btn-right']);
        }

        $output .= '<table class="table table-striped table-bordered tablest" id="lectures">';
        for ($tr = 0; $tr <= $rows_num; $tr++) {
            if (!$tr) {
                $output .= '<thead><tr>';
                for ($td = 0; $td <= $cols_num; $td++) {
                    if (!$td) {
                        $output .= '<th>Пара</th>';
                    } else {
                        $days = array ("Понеділок", "Вівторок", "Середа", "Четвер", "П'ятниця", "Субота", "Неділя");
                        $day = $formatter->asDate($datestart, "l");
                        switch ($day) {
                            case 'Monday':
                                $day = $days[0];
                                break;
                            case 'Tuesday':
                                $day = $days[1];
                                break;
                            case 'Wednesday':
                                $day = $days[2];
                                break;
                            case 'Thursday':
                                $day = $days[3];
                                break;
                            case 'Friday':
                                $day = $days[4];
                                break;
                            case 'Saturday':
                                $day = $days[5];
                                break;
                            case 'Sunday':
                                $day = $days[6];
                                break;
                        }
                        $date = $formatter->asDate($datestart, "dd.MM.yyyy");
                        $output .= '<th>'. $date . '<br/>' . $day . '</th>';
                        $datestart = $datestart + 86400;
                    }
                }
                $output .= '</tr></thead>';
            } else {
                $output .= '<tr>';
                $i = 1;
                for ($td = 0; $td <= $cols_num; $td++) {
                    if ($td == 0) {
                        $output .= '<td><div class="lect-num">' . $tr . '</div></td>';
                    } else {
                        //$output .= '<td> '.$tr.$td.' </td>';
                        $output .= '<td>';
                        $class_bg = 'light';
                        foreach ($input_array as $cell) {
                            if (($cell['x'] == $td) && ($cell['y'] == $tr)) {
                                $corpsName = Corps::find()
                                    ->asArray()
                                    ->select('corps_name')
                                    ->where(['=', 'ID', $cell['corps_id']])
                                    ->one();
                                $corpsName = $corpsName['corps_name'];

                                $audienceName = Audience::find()
                                    ->asArray()
                                    ->select('name')
                                    ->where(['=', 'ID', $cell['audience_id']])
                                    ->one();
                                $audienceName = $audienceName['name'];

                                $audienceNum = Audience::find()
                                    ->asArray()
                                    ->select('num')
                                    ->where(['=', 'ID', $cell['audience_id']])
                                    ->one();
                                $audienceNum = $audienceNum['num'];

                                $teacher = User::find()
                                    ->asArray()
                                    ->select('id, firstname, middlename, lastname')
                                    ->where(['=', 'id', $cell['teacher_id']])
                                    ->one();
                                $teacherName = $teacher['firstname'] . " " . $teacher['lastname'];

                                $groupName = Groups::find()
                                    ->asArray()
                                    ->select('name')
                                    ->where(['=', 'id', $cell['group_id']])
                                    ->one();
                                $groupName = $groupName['name'];

                                $groupName = explode(" ", $groupName);
                                $groupName = $groupName[0];

                                $subjName = Subjects::find()
                                    ->asArray()
                                    ->select('name')
                                    ->where(['=', 'id', $cell['subjects_id']])
                                    ->one();
                                $subjName = $subjName['name'];

                                $half = Timetable::find()
                                    ->asArray()
                                    ->select('half')
                                    ->where(['=', 'x', $td])
                                    ->andWhere(['=', 'y', $tr])
                                    ->andWhere(['=', 'subjects_id', $cell['subjects_id']])
                                    ->one();
                                $half = $half['half'];

                                $half_class = "full";
                                if ($half == 1) {
                                    $half_class = "half";
                                }

                                $curdate = $date_array['datestart'];
                                $curdate = (int)$curdate;
                                $curdate = $curdate + 86400 * ($td - 1);

                                $output .= '<div class="' . $class_bg . ' ' . $half_class . '">';
                                $output .= '<p> К.: ' . $corpsName . '<br />';
                                $output .= 'А.: ' . $audienceNum . '</p>';
                                $output .= '<p>В.: ' . $teacherName . '</p>';
                                $output .= '<p>Г.: ' . $groupName . '</p>';
                                $output .= '<p><a href="/timetable-parts/freetime/?group='. $cell['group_id']
                                    .'&subject='.$cell['subjects_id']
                                    .'&teacher='.$teacher['id'].'&date='.$curdate.'" target="_blank">П.: ' . $cell['title'] . '</a></p>';
                                if (isset(Yii::$app->user->identity->role)) {
                                    if (Yii::$app->user->identity->role == 1) {
                                        $output .= '<p class="align-center"><br/><!--<a href="/timetable/update/' . $cell["id"] . '">Редагувати</a> | -->
                                                            <a href="/timetable/delete/' . $cell["id"] . '?tp=' . $id . '" class="btn btn-danger align-center">Видалити</a></p>';
                                    }
                                }
                                $output .= '</div>';
                                switch ($class_bg) {
                                    case 'dark':
                                        $class_bg = 'light';
                                        break;
                                    case 'light':
                                        $class_bg = 'dark';
                                        break;
                                }
                            }
                        }
                        if (isset(Yii::$app->user->identity->role)) {
                            if (Yii::$app->user->identity->role == 1) {
                                $curdate = $date_array['datestart'];
                                $curdate = (int)$curdate;
                                $curdate = $curdate + 86400 * ($td - 1);
                                //$curdate = $formatter->asDate($curdate, "dd.MM.yyyy");
                                $output .= '<div class="create"><p class="align-center"><br/><a class="btn btn-primary" href="/timetable/create/?tp=' . $id . '&x=' . $td . '&y=' . $tr . '&date=' . $curdate . '">Додати заняття (' . $tr . ' пара)</a></div>';
                            }
                        } else {
                            $output .=  '<div></div>';
                        }

                        $output .= '</td>';

                    }

                }
                $output .= '</tr>';
            }
        }
        $output .= "</table>";

        return $output;


    }

    public function renderTableForMont($id, $teacherID, $groupID) {
        $output = '';

        //zecho "$id";

        if ( $teacherID && $groupID ) {
            $input_array = Timetable::find()
                ->asArray()
                ->where( [ '=', 'mont', $id ] )
                ->andWhere( [ '=', 'teacher_id', $teacherID ] )
                ->andWhere( [ '=', 'group_id', $groupID ] )
                ->all();
        }
        if( $teacherID && !$groupID) {
            $input_array = Timetable::find()
                ->asArray()
                ->where( [ '=', 'mont', $id ] )
                ->andWhere( [ '=', 'teacher_id', $teacherID ] )
                ->all();
        }
        if( $groupID && !$teacherID) {
            $input_array = Timetable::find()
                ->asArray()
                ->where( [ '=', 'mont', $id ] )
                ->andWhere( [ '=', 'group_id', $groupID ] )
                ->all();
        }
        if( !$teacherID && !$groupID ) {
            $input_array = Timetable::find()
                ->asArray()
                ->where( [ '=', 'mont', $id ] )
                ->all();
        }

        $timetable = new TimetableParts();

        $date_array = $timetable
            ->find()
            ->asArray()
            ->where(['mont' => $id])
            ->all();

        //print_r($date_array);

        $date_array = $date_array[0];

        //print_r($date_array);

        $datestart = $date_array['datestart'];
        $datestart = (int)$datestart;
        $dateend = $date_array['dateend'];
        $dateend = (int)$dateend;
        $cols_num = $date_array['cols']; //кол-во дней
        $rows_num = $date_array['rows'];
        $mont = $date_array['mont'];

        //print_r($input_array);

        foreach ($input_array as &$value) {
            $value['date'] = (strtotime( $value['date']) - $datestart) / 86400 + 1;

        }

        $formatter = new \yii\i18n\Formatter;

        $output .= '<h2>Розклад з ' . $formatter->asDate($datestart, "dd.MM.yyyy") . ' по ' . $formatter->asDate($dateend, "dd.MM.yyyy") . '</h2>';

        if($groupID) {
            $output .= Html::a('Роздрукувати розклад групи', ['timetable/print', 'table_id' => $id, 'group_id' => $groupID], ['class' => 'btn btn-success btn-right']);
        }

        if($teacherID) {
            $output .= Html::a('Роздрукувати розклад викладача', ['timetable/print', 'table_id' => $id, 'teacher_id' => $teacherID], ['class' => 'btn btn-success btn-right']);
        }

        $output .= '<table class="table table-striped table-bordered" id="lectures">';
        for ($tr = 0; $tr <= $rows_num; $tr++) {
            if (!$tr) {
                $output .= '<thead><tr>';
                for ($td = 0; $td <= $cols_num; $td++) {
                    if (!$td) {
                        $output .= '<th>Пара</th>';
                    } else {
                        $days = array ("Понеділок", "Вівторок", "Середа", "Четвер", "П'ятниця", "Субота", "Неділя");
                        $day = $formatter->asDate($datestart, "l");
                        switch ($day) {
                            case 'Monday':
                                $day = $days[0];
                                break;
                            case 'Tuesday':
                                $day = $days[1];
                                break;
                            case 'Wednesday':
                                $day = $days[2];
                                break;
                            case 'Thursday':
                                $day = $days[3];
                                break;
                            case 'Friday':
                                $day = $days[4];
                                break;
                            case 'Saturday':
                                $day = $days[5];
                                break;
                            case 'Sunday':
                                $day = $days[6];
                                break;
                        }
                        $date = $formatter->asDate($datestart, "dd.MM.yyyy");
                        $output .= '<th>'. $date . '<br/>' . $day . '</th>';
                        $datestart = $datestart + 86400;
                    }
                }
                $output .= '</tr></thead>';
            } else {
                $output .= '<tr>';
                $i = 1;
                for ($td = 0; $td <= $cols_num; $td++) {
                    if ($td == 0) {
                        $output .= '<td><div class="lect-num">' . $tr . '</div></td>';
                    } else {
                        //$output .= '<td> '.$tr.$td.' </td>';
                        $output .= '<td>';
                        $class_bg = 'light';
                        foreach ($input_array as $cell) {
                            if(($cell['x'] == $td) && ($cell['y'] == $tr) ) {
                                $corpsName = Corps::find()
                                    ->asArray()
                                    ->select('corps_name')
                                    ->where(['=', 'ID', $cell['corps_id']])
                                    ->one();
                                $corpsName = $corpsName['corps_name'];

                                $audienceName = Audience::find()
                                    ->asArray()
                                    ->select('name')
                                    ->where(['=', 'ID', $cell['audience_id']])
                                    ->one();
                                $audienceName = $audienceName['name'];

                                $teacher = User::find()
                                    ->asArray()
                                    ->select('firstname, middlename, lastname')
                                    ->where(['=', 'id', $cell['teacher_id']])
                                    ->one();
                                $teacherName = $teacher['firstname']." ".$teacher['lastname'];

                                $groupName = Groups::find()
                                    ->asArray()
                                    ->select('name')
                                    ->where(['=', 'id', $cell['group_id']])
                                    ->one();
                                $groupName = $groupName['name'];

                                $subjName = Subjects::find()
                                    ->asArray()
                                    ->select('name')
                                    ->where(['=', 'id', $cell['subjects_id']])
                                    ->one();
                                $subjName = $subjName['name'];

                                $half = Timetable::find()
                                    ->asArray()
                                    ->select('half')
                                    ->where(['=', 'x', $td])
                                    ->andWhere(['=', 'y', $tr])
                                    ->andWhere(['=', 'subjects_id', $cell['subjects_id']])
                                    ->one();
                                $half = $half['half'];

                                $half_class = "full";
                                if($half == 1) {
                                    $half_class = "half";
                                }

                                $output .= '<div class="'.$class_bg.' '.$half_class.'">';
                                $output .= '<p> Корпус: '.$corpsName.'<br />';
                                $output .= 'Аудиторія: '.$audienceName.'</p>';
                                $output .= '<p>Викладач: '.$teacherName.'</p>';
                                $output .= '<p>Группа: '.$groupName.'</p>';
                                $output .= '<p>Предмет: '.$subjName.'</p>';
                                if(Yii::$app->user->identity->role==1) {
                                    $output .= '<p class="align-center"><br/><!--<a href="/timetable/update/'.$cell["id"].'">Редагувати</a> | -->
														<a href="/timetable/delete/'.$cell["id"].'?tp='.$id.'" class="btn btn-danger align-center">Видалити</a></p>';
                                }
                                $output .= '</div>';
                                switch ($class_bg) {
                                    case 'dark':
                                        $class_bg = 'light';
                                        break;
                                    case 'light':
                                        $class_bg = 'dark';
                                        break;
                                }
                            }
                        }
                        if(Yii::$app->user->identity->role==1) {
                            $curdate = $date_array['datestart'];
                            $curdate = (int)$curdate;
                            $curdate = $curdate + 86400*($td-1);
                            //$curdate = $formatter->asDate($curdate, "dd.MM.yyyy");
                            $output .= '<div><p class="align-center"><br/><a class="btn btn-primary" href="/timetable/create/?tp='.$id.'&x='.$td.'&y='.$tr.'&date='.$curdate.'">Додати заняття ('.$tr.' пара)</a></div>';
                        }
                        $output .= '</td>';

                    }

                }
                $output .= '</tr>';
            }
        }
        $output .= "</table>";

        return $output;


    }

	public function getLectureTime($corpsId) {
        if($corpsId == 0) {
            $lecturetime_values = LectureTable::find()->asArray()
                ->select(["ID", "CONCAT( time_start, ' - ', time_stop) AS time"])
                ->orderBy('time_start')
                ->all();
            $lecturetime_names = ArrayHelper::getColumn($lecturetime_values, 'time');
            $lecturetime_ids = ArrayHelper::getColumn($lecturetime_values, 'ID');

            $lecturetime = array_combine($lecturetime_ids, $lecturetime_names);
            return $lecturetime;
        } else {
            $lecturetime_values = LectureTable::find()->asArray()
                ->select(["ID", "CONCAT( time_start, ' - ', time_stop) AS time"])
                ->where(['=', 'corps_id', $corpsId])
                ->orderBy('time_start')
                ->all();
            $lecturetime_names = ArrayHelper::getColumn($lecturetime_values, 'time');
            $lecturetime_ids = ArrayHelper::getColumn($lecturetime_values, 'ID');

            $lecturetime = array_combine($lecturetime_ids, $lecturetime_names);
            return $lecturetime;
        }
	}

	public function getAudienceNames() {
		$audience_values = Audience::find()->asArray()
            ->select(["ID", "corps_id", "CONCAT('№ ', num, ' - ', name) AS full_name"])
			//->where(['role' => 2, 'status' => 1])
            ->orderBy('ID')
            ->all();
		$audience_names = ArrayHelper::getColumn($audience_values, 'full_name');
		$audience_ids = ArrayHelper::getColumn($audience_values, 'ID');
		$corps_ids = ArrayHelper::getColumn($audience_values, 'corps_id');

		foreach ($corps_ids as $id) {
			$corps_names[] = Corps::find()->asArray()
                ->select(["corps_name"])
                ->where(['ID' => $id])
                ->orderBy('ID')
                ->one();
		}

		$corps_names = ArrayHelper::getColumn($corps_names, 'corps_name');

		for($i = 0; $i < count($audience_names); $i++ ) {
			$audience_names[$i] = "Корпус: ".$corps_names[$i]." || Аудиторія: ".$audience_names[$i];
		}

		$audience = array_combine($audience_ids, $audience_names);
		return $audience;
	}

	public function getTeachersNames() {

		$teacher_values = User::find()->asArray()
            ->select(['id', "CONCAT(firstname, ' ', middlename, ' ',lastname) AS full_name"])
            ->where(['role' => 2, 'status' => 1])
            ->orderBy('id')
            ->all();
		$teacher_names = ArrayHelper::getColumn($teacher_values, 'full_name');
		$teacher_ids = ArrayHelper::getColumn($teacher_values, 'id');

		$teachers = array_combine($teacher_ids, $teacher_names);

		return $teachers;
	}

	public function getTeacherName($id) {

		$teacher_values = User::find()->asArray()
		                      ->select(['id', "CONCAT(firstname, ' ', middlename, ' ',lastname) AS full_name"])
		                      ->where(['=', 'id', $id])
		                      ->one();

		$teacher = $teacher_values['full_name'];

		return $teacher;
	}

	public function getSubjectsNames() {

		$subjects_values = Subjects::find()->asArray()->select(['ID', 'name'])
		                      ->orderBy('ID')
		                      ->all();
		$subjects_names = ArrayHelper::getColumn($subjects_values, 'name');
		$subjects_ids = ArrayHelper::getColumn($subjects_values, 'ID');

		$subjects = array_combine($subjects_ids, $subjects_names);

		return $subjects;
	}

	public function getGroupsNames() {

		$groups_values = Groups::find()->asArray()->select(['ID', 'name'])
		                           ->orderBy('ID')
		                           ->all();
		$groups_names = ArrayHelper::getColumn($groups_values, 'name');
		$groups_ids = ArrayHelper::getColumn($groups_values, 'ID');

		$groups = array_combine($groups_ids, $groups_names);

		return $groups;
	}

	public function getGroupName($id) {
		$group_values = Groups::find()->asArray()
		                      ->select(['ID', 'name'])
		                      ->where(['=', 'ID', $id])
		                      ->one();

		$group = $group_values['name'];

		return $group;
	}

    public function getCorpsNames() {

        $corps_values = Corps::find()->asArray()->select(['ID', 'corps_name'])
            ->orderBy('ID')
            ->all();
        $corps_names = ArrayHelper::getColumn($corps_values, 'corps_name');
        $corps_ids = ArrayHelper::getColumn($corps_values, 'ID');

        $corps = array_combine($corps_ids, $corps_names);

        return $corps;
    }

    public function getAudienceList($corpsId) {
        $audience_values = Audience::find()->asArray()->select(['ID', 'name'])
            ->where(['=', 'corps_id', $corpsId])
            ->orderBy('ID')
            ->all();

        $audience = [];
        foreach ($audience_values as $value) {
            $audience[] = array('id' => $value['ID'], 'name' => $value['name'] );
        }

        return $audience;
    }

    public function getLectureList($corpsId) {
        $course_ids[] = Corps::find()->asArray()
            ->select(["corps_name"])
            ->where(['ID' => $corpsId])
            ->orderBy('ID')
            ->one();

        $lecture_values = LectureTable::find()->asArray()
            ->select(["ID", "CONCAT( time_start, ' - ', time_stop) AS time"])
            ->where(['=', 'corps_id', $corpsId])
            ->orderBy('time_start')
            ->all();

        $lecture = [];
        foreach ($lecture_values as $value) {
            $lecture[] = array('id' => $value['ID'], 'name' => $value['time'] );
        }

        return $lecture;
    }

    public function getGroupList($groupId) {
        $course_ids[] = Groups::find()->asArray()
            ->select(["course"])
            ->where(['ID' => $groupId])
            ->one();
        $course_id = ArrayHelper::getColumn($course_ids, 'course');

        $groupLessons = Lessons::find()
            ->asArray()
            ->select('subject_id')
            ->where(['course_id' => $course_id])
            ->all();

        $subjects = [];
        foreach ($groupLessons as $value) {
            $subject_name = Subjects::find()
                ->asArray()
                ->select(["name"])
                ->where(['ID' => $value['subject_id']])
                ->one();
            $subject_name = $subject_name['name'];

            $subjects[] = array('id' => $value['subject_id'], 'name' => $subject_name );
        }

        return $subjects;
    }

    public function getGroupListRnp($groupId) {
        $course_ids[] = Groups::find()->asArray()
            ->select(["course"])
            ->where(['ID' => $groupId])
            ->one();
        $course_id = ArrayHelper::getColumn($course_ids, 'course');
        $rnp = Rnps::find()->where(['prof_id' => $course_id])->asArray()->one();
        $rnp_id = $rnp['ID'];
        $rnpSubjects = RnpSubjects::find()->where(['rnp_id' => $rnp_id])->asArray()->all();
        $subjects = array();
        foreach ($rnpSubjects as $value) {
            $subjects[] = array('id' => $value['ID'], 'name' => $value['title'] );
        }
        return $subjects;
    }

    public function getTeacherListRnp($subjectId) {
        $teachers = Nakaz::find()->select('teacher_id')->where(['subject_id' => $subjectId])->asArray()->groupBy('teacher_id')->all();
        $subjects = array();
        foreach ($teachers as $teacher) {
            $teacher_name = User::find()->select(['name' => 'CONCAT(lastname, " ", firstname, " ", middlename)'])->where(['id' => $teacher['teacher_id']])->asArray()->one();
            $teacher_name = $teacher_name['name'];
            $subjects[] = array('id' => $teacher['teacher_id'], 'name' => $teacher_name );
        }
        return $subjects;
    }
}
