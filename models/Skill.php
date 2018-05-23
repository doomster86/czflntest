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

    public function getTeachers($id) {
        $teachersArray = TeacherMeta::find()->asArray()
            ->leftJoin('user', 'teacher_meta.user_id = user.id')
            ->where(['teacher_meta.skill_id' => $id])
            ->select(['user_id', 'firstname', 'middlename', 'lastname', 'teacher_type', 'role'])
            ->all();
        if(!empty($teachersArray)) {
            return $teachersArray;
        } else {
            return NULL;
        }
    }
}
