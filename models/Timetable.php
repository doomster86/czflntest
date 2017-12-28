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
            [['corps_id', 'audience_id', 'subjects_id', 'teacher_id', 'group_id', 'lecture_id', 'status','part_id', 'x', 'y', 'date'], 'required'],
            [['corps_id', 'audience_id', 'subjects_id', 'teacher_id', 'group_id', 'lecture_id', 'status','part_id', 'x', 'y', 'date'], 'integer'],
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
        $input_array = Timetable::find()
            ->asArray()
            ->where(['=', 'part_id', $id])
            ->all();
        //v($input_array);


        /*
        был
        $input_array = array(
            0 =>
                array(
                    "date" => "01.01.2018", //колонка
                    "lectureN" => "1", //строка
                    "corps" => "Корпус 1",
                    "audience" => "Аудиторія №1",
                    "subject" => "Практика на заводі",
                    "teacher" => "Коритувач Т.А.",
                    "group" => "ГР-1",
                    "part" => "56",
                ),
        );

        приходит
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

        $date_array = $timetable->find()->asArray()->where(['id' => $id])->all();
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
        $output .= '<div class="table-responsive"><table class="table table-striped table-bordered" id="lectures">';
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

                                $teacherName = User::find()
                                    ->asArray()
                                    ->select('firstname, middlename, lastname')
                                    ->where(['=', 'id', $cell['teacher_id']])
                                    ->one();
                                $teacherName = $teacherName['firstname']." ".$teacherName['lastname'];

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

                                $output .= '<div class="'.$class_bg.'">';
                                $output .= '<p> Корпус: '.$corpsName.'<br />';
                                $output .= 'Аудиторія: '.$audienceName.'</p>';
                                $output .= '<p>Викладач: '.$teacherName.'</p>';
                                $output .= '<p>Группа: '.$groupName.'</p>';
                                $output .= '<p>Предмет: '.$subjName.'</p>';
                                $output .= '</div>';
                                switch ($class_bg) {
                                    case 'dark':
                                        $class_bg = 'light';
                                        break;
                                    case 'light':
                                        $class_bg = 'dark';
                                        break;
                                }
                            } else {
                                $output .= '<div></div>';
                            }
                        }
                        $output .= '</td>';

                    }

                }
                $output .= '</tr>';
            }
        }
        $output .= "</table></div>";

        return $output;


    }

}
