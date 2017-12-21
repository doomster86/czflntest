<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use \app\models\TimetableParts;
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
            [['corps_id', 'audience_id', 'subjects_id', 'teacher_id', 'group_id', 'lecture_id', 'date', 'status','part_id', 'x', 'y'], 'required'],
            [['corps_id', 'audience_id', 'subjects_id', 'teacher_id', 'group_id', 'lecture_id', 'status','part_id', 'x', 'y'], 'integer'],
            [['date'], 'string'],
            [['corps_id'], 'exist', 'skipOnError' => true, 'targetClass' => Corps::className(), 'targetAttribute' => ['corps_id' => 'ID']],
            [['audience_id'], 'exist', 'skipOnError' => true, 'targetClass' => Audience::className(), 'targetAttribute' => ['audience_id' => 'ID']],
            [['subjects_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subjects::className(), 'targetAttribute' => ['subjects_id' => 'ID']],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAudience()
    {
        return $this->hasOne(Audience::className(), ['ID' => 'audience_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasOne(Subjects::className(), ['ID' => 'subjects_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(User::className(), ['id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['ID' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLecture()
    {
        return $this->hasOne(LectureTable::className(), ['ID' => 'lecture_id']);
    }

    public function getPartId()
    {
        return $this->hasOne(LectureTable::className(), ['id' => 'part_id']);
    }

    public function renderTable($id) {
        $output = '';

        $input_array = array(
            0 =>
                array(
                    "date" => "25.12.17", //колонка
                    "lectureN" => "1", //строка
                    /* от сих */
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №1",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    /* до сих в ячейку */
                    "part" => "48", //timetable_parts id
                ),
            1 =>
                array(
                    "date" => "25.12.17", //25.12.17
                    "lectureN" => "2",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №2",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            2 =>
                array(
                    "date" => "25.12.17",//25.12.17
                    "lectureN" => "3",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №3",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            3 =>
                array(
                    "date" => "25.12.17", //25.12.17
                    "lectureN" => "4",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №4",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            4 =>
                array(
                    "date" => "26.12.17", //26.12.17
                    "lectureN" => "2",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №5",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            5 =>
                array(
                    "date" => "26.12.17", //26.12.17
                    "lectureN" => "3",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №6",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            6 =>
                array(
                    "date" => "26.12.17", //26.12.17
                    "lectureN" => "4",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №7",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            7 =>
                array(
                    "date" => "27.12.17", //27.12.17
                    "lectureN" => "1",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №8",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            8 =>
                array(
                    "date" => "27.12.17", //27.12.17
                    "lectureN" => "2",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №9",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            9 =>
                array(
                    "date" => "27.12.17", //27.12.17
                    "lectureN" => "4",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №10",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            10 =>
                array(
                    "date" => "28.12.17", //28.12.17
                    "lectureN" => "1",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №11",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            11 =>
                array(
                    "date" => "28.12.17", //28.12.17
                    "lectureN" => "2",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №12",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            12 =>
                array(
                    "date" => "29.12.17", //29.12.17
                    "lectureN" => "3",
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №13",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "48",
                ),
            13 =>
                array(
                    "date" => "29.12.17", //29.12.17
                    "lectureN" => "4",

                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №14",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",

                    "part" => "48",
                ),
            14 =>
                array(
                    "date" => "29.12.17", //29.12.17
                    "lectureN" => "4",

                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №14",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",

                    "part" => "48",
                ),
        );



        //$add_zero_element = array( 0 => 'Оберіть корпус');
        //$input_array = ArrayHelper::merge($add_zero_element, $input_array);

        //$output .= v($input_array);


        $timetable = new TimetableParts();

        $date_array = $timetable->find()->asArray()->where(['id' => $id])->all();
        $date_array = $date_array[0];
        $datestart = $date_array['datestart'];
        $datestart = (int)$datestart;
        $dateend = $date_array['dateend'];
        $dateend = (int)$dateend;
        $cols_num = $date_array['cols']; //кол-во дней
        $rows_num = $date_array['rows'];

        foreach ($input_array as &$value) {
            //$value['date'] = ( (strtotime( $value['date'] ) - $datestart ) % 86400 ) + 1 ;
            $value['date'] = (strtotime( $value['date']) - $datestart)/86400+1;
            //$value['date'] =  strtotime( $value['date']);
        }
        $output .= v($input_array);
        //$cols = '';
        //$rows = '';
        $formatter = new \yii\i18n\Formatter;


        //$datestart = $input_array[0]['date'];
        //$dateend = $input_array[count($input_array)-1]['date'];
        //$output .= $cols_num;
        $output .= '<h2>Розклад з ' . $formatter->asDate($datestart, "dd.MM.yyyy") . ' по ' . $formatter->asDate($dateend, "dd.MM.yyyy") . '</h2>';
        $output .= '<table class="table table-striped table-bordered">';
        for ($tr = 0; $tr <= $rows_num; $tr++) {
            if (!$tr) {
                $output .= '<thead><tr>';
                for ($td = 0; $td <= $cols_num; $td++) {
                    if (!$td) {
                        $output .= '<th>#</th>';
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
                    if (!$td) {
                        $output .= '<td>' . $tr . '</td>';
                    } else {
                        //$output .= '<td> '.$tr.$td.' </td>';

                        $output .= '<td>';
                        foreach ($input_array as $cell) {
                            if(($cell['date'] == $td) && ($cell['lectureN'] == $tr) ) {

                                $output .= '<p>'.$cell['corps'].'</p>';
                                $output .= '<p>'.$cell['audience'].'</p>';
                                $output .= '<p>'.$cell['teacher'].'</p>';
                                $output .= '<p>'.$cell['group'].'</p>';
                                $output .= '<hr/>';
                            } else {
                                $output .= '';
                            }
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

}
