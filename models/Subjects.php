<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subjects".
 *
 * @property integer $ID
 * @property string $name
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
            [['name', 'teacher', 'dur_lesson', 'dur_break', 'max_week'], 'required', 'message'=>'Обов\'язкове поле'],
            ['name', 'string', 'min' => 3, 'max' => 255, 'message'=>'Мін 3 літери'],
            [['dur_lesson','dur_break'], 'number', 'min' => 1, 'message' => 'Тільки цифри'],
            [['max_week'], 'integer', 'min' => 0]
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
// шаблон, поменяй имена переменных
    /*
    public function getCorps() {
        return $this->hasOne(Corps::className(), ['ID' => 'corps_id']);
    }

    public function getCorpsName() {
        return $this->corps->name;
    }

    public function getCorpsNames() {

        $corps_values = Corps::find()->asArray()->select('name')->orderBy('ID')->all();
        $corps_values = ArrayHelper::getColumn($corps_values, 'name');

        $corps_ids = Corps::find()->asArray()->select('ID')->orderBy('ID')->all();
        $corps_ids = ArrayHelper::getColumn($corps_ids, 'ID');

        $corps = array_combine($corps_ids,$corps_values);

        $corps_add = array( 0 => 'Оберіть корпус');
        $corps = ArrayHelper::merge($corps_add, $corps);

        return $corps;
    }
*/

    public function getTeachersNames() {
        $teachers = [];
        return $teachers;
    }
}
