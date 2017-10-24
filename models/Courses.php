<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "courses".
 *
 * @property integer $ID
 * @property string $name
 * @property string $subject
 * @property integer $group_id
 * @property integer $pract
 * @property integer $worklect
 * @property integer $teorlect
 */
class Courses extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'courses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'subject', 'group_id', 'pract', 'worklect', 'teorlect'], 'required'],
            [['subject'], 'string'],
            [['group_id', 'pract', 'worklect', 'teorlect'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'name' => 'Name',
            'subject' => 'Subject',
            'group_id' => 'Group ID',
            'pract' => 'Pract',
            'worklect' => 'Worklect',
            'teorlect' => 'Teorlect',
        ];
    }
}
