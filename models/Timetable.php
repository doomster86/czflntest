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

        $timetable = new TimetableParts();
        /*
        $rows = $timetable->find()->asArray()->select('rows')->where(['id' => $id])->all();
        $rows = ArrayHelper::getColumn($rows, 'rows');
        $rows = $rows[0];
        $cols = $timetable->find()->asArray()->select('cols')->where(['id' => $id])->all();
        $cols = ArrayHelper::getColumn($cols, 'cols');
        $cols = $cols[0];
        */
        $date_array = $timetable->find()->asArray()->where(['id' => $id])->all();
        $date_array = $date_array[0];
        $datestart = $date_array['datestart'];
        $datestart = (int)$datestart;
        $cols = $date_array['cols'];
        $rows = $date_array['rows'];

        $output = '<table class="table table-striped table-bordered">';
        for ($tr = 0; $tr <= $rows; $tr++) {
            if (!$tr) {
                $output .= '<thead><tr>';
                for ($td = 0; $td <= $cols; $td++) {
                    if (!$td) {
                        $output .= '<th>#</th>';
                    } else {
                        //date('l', $datestart)
                        $output .= '<th>'.date('l', $datestart).'</th>';
                        $datestart = $datestart + 86400;
                    }
                }
                $output .= '</tr></thead>';
            } else {
                $output .= '<tr>';
                for ($td = 0; $td <= $cols; $td++) {
                    if (!$td) {
                        $output .= '<td>' . $tr . '</td>';
                    } else {
                        $output .= '<td>' . 'tr = ' . $tr . '; td = ' . $td . '</td>';
                    }
                }
                $output .= '</tr>';
            }
        }
        $output .= "</table>";

        return $output;

    }

}
