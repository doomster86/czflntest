<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rank".
 *
 * @property integer $ID
 * @property string $rank_name
 *
 * @property TeacherMeta[] $teacherMetas
 */
class Rank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rank_name'], 'required', 'message' => 'Обов\'язкове поле'],
            [['rank_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'rank_name' => 'Rank Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherMetas()
    {
        return $this->hasMany(TeacherMeta::className(), ['rank_id' => 'ID']);
    }
}
