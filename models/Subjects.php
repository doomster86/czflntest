<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "subjects".
 *
 * @property integer $ID
 * @property string $name
 * @property integer $teacher_id
 * @property integer $audience_id
 * @property integer $required
 * @property integer $max_week
 *
 * @property CountLecture[] $countLectures
 * @property Groups[] $groups
 * @property Schedule[] $schedules
 * @property Audience $audience
 * @property User $teacher
 */
class Subjects extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'subjects';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'teacher_id', 'audience_id', 'max_week'], 'required', 'message'=>'Обов\'язкове поле'],
            [['teacher_id', 'audience_id', 'required', 'max_week'], 'integer', 'min' => 0, 'message' => 'Тільки цифри'],
            [['name'], 'string', 'min' => 3, 'max' => 255, 'message'=>'Мін 3 літери'],
            [['audience_id'], 'exist', 'skipOnError' => true, 'targetClass' => Audience::className(), 'targetAttribute' => ['audience_id' => 'ID']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'name' => 'Предмет',
            'teacher_id' => 'Викладач (id)',
            'userName' => 'Викладач',
            'audience_id' => 'Аудиторія',
            'required' => 'Required',
            'max_week' => 'Макс. на тиждень',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountLectures() {
        return $this->hasMany(CountLecture::className(), ['subject_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups() {
        return $this->hasMany(Groups::className(), ['ID' => 'group_id'])->viaTable('count_lecture', ['subject_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules() {
        return $this->hasMany(Schedule::className(), ['subjects_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAudience() {
        return $this->hasOne(Audience::className(), ['ID' => 'audience_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'teacher_id']);
    }

    /* функции для вывода в index и поиска */

    public function getTeacherName() {
        return $this->user->firstname . ' ' . $this->user->middlename . ' ' . $this->user->lastname;
    }

    public function getTeachersNames() {

        $teacher_values = User::find()->asArray()->select(['id', "CONCAT(firstname, ' ', middlename, ' ',lastname) AS full_name"])
            ->where(['role' => 2, 'status' => 1])
            ->orderBy('id')
            ->all();
        $teacher_names = ArrayHelper::getColumn($teacher_values, 'full_name');
        $teacher_ids = ArrayHelper::getColumn($teacher_values, 'id');

        $teachers = array_combine($teacher_ids, $teacher_names);

        //$corps_add = array( 0 => 'Оберіть викладача');
        //$corps = ArrayHelper::merge($corps_add, $corps);

        return $teachers;
    }

    public function getAudienceNames() {

        $audience_values = Audience::find()->asArray()->select(["ID", "corps_id", "CONCAT('№ ', num, ' - ', name) AS full_name"])
            //->where(['role' => 2, 'status' => 1])
            ->orderBy('ID')
            ->all();
        $audience_names = ArrayHelper::getColumn($audience_values, 'full_name');
        $audience_ids = ArrayHelper::getColumn($audience_values, 'ID');
        $corps_ids = ArrayHelper::getColumn($audience_values, 'corps_id');

        foreach ($corps_ids as $id) {
            $corps_names[] = Corps::find()->asArray()->select(["corps_name"])
                ->where(['ID' => $id])
                ->orderBy('ID')
                ->one();
        }

        $corps_names = ArrayHelper::getColumn($corps_names, 'corps_name');

        for($i = 0; $i < count($audience_names); $i++ ) {
            $audience_names[$i] = "Корпус: ".$corps_names[$i]." || Аудиторія: ".$audience_names[$i];
        }

        $audience = array_combine($audience_ids, $audience_names);
        //$corps_add = array( 0 => 'Оберіть викладача');
        //$corps = ArrayHelper::merge($corps_add, $corps);
        return $audience;
    }

    public function getAudienceName() {
        return Audience::getCorpsNameByAudienceID($this->audience->ID). ' ' . $this->audience->num. ' ' . $this->audience->name;
        //$audience_id = $this->audience->ID;
        //return Audience::getCorpsNameByID(3);
        //return  $this->audience->num. ' ' . $this->audience->name;
    }

}
