<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "subjects".
 *
 * @property integer $ID
 * @property string $name
 */
class Practice extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'practice';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'master_id', 'max_week'], 'required', 'message'=>'Обов\'язкове поле'],
            ['name', 'string', 'min' => 3, 'max' => 255, 'message'=>'Мін 3 літери'],
            [['max_week'], 'integer', 'min' => 0, 'message' => 'Тільки цифри']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'name' => 'Назва',
        ];
    }

    public function getTeacher() {
        return $this->hasOne(User::className(), ['id' => 'master_id']);
    }

    public function getTeacherName() {
        $firstname = $this->teacher->firstname;
        $middlenamde = $this->teacher->middlename;
        $lastname = $this->teacher->lastname;
        return $firstname." ".$middlenamde." ".$lastname;
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

}