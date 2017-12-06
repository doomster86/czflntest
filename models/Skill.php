<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skill".
 *
 * @property integer $ID
 * @property string $skill_name
 *
 * @property TeacherMeta[] $teacherMetas
 */
class Skill extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'skill';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['skill_name'], 'required', 'message' => 'Обов\'язкове поле'],
            [['skill_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'skill_name' => 'Кваліфікація',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherMetas() {
        return $this->hasMany(TeacherMeta::className(), ['skill_id' => 'ID']);
    }
}
