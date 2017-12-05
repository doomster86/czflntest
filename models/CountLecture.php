<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "count_lecture".
 *
 * @property integer $id
 * @property integer $subject_id
 * @property integer $group_id
 * @property integer $count
 *
 * @property Groups $group
 * @property Subjects $subject
 */
class CountLecture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'count_lecture';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject_id', 'group_id', 'count'], 'required'],
            [['subject_id', 'group_id', 'count'], 'integer'],
            [['subject_id', 'group_id'], 'unique', 'targetAttribute' => ['subject_id', 'group_id'], 'message' => 'The combination of Subject ID and Group ID has already been taken.'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['group_id' => 'ID']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subjects::className(), 'targetAttribute' => ['subject_id' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject_id' => 'Subject ID',
            'group_id' => 'Group ID',
            'count' => 'Count',
        ];
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
    public function getSubject()
    {
        return $this->hasOne(Subjects::className(), ['ID' => 'subject_id']);
    }
}
