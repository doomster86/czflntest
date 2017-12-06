<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "degree".
 *
 * @property integer $ID
 * @property string $degree_name
 *
 * @property TeacherMeta[] $teacherMetas
 */
class Degree extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'degree';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['degree_name'], 'required', 'message' => 'Обов\'язкове поле'],
            [['degree_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'degree_name' => 'Ступінь',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherMetas() {
        return $this->hasMany(TeacherMeta::className(), ['degree_id' => 'ID']);
    }
}
