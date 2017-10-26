<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "courses".
 *
 * @property integer $ID
 * @property string $name
 * @property string $subject
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
            [['name', 'subject', 'pract', 'worklect', 'teorlect'], 'required'],
            [['subject'], 'string'],
            [['pract', 'worklect', 'teorlect'], 'integer'],
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
            'pract' => 'Pract',
            'worklect' => 'Worklect',
            'teorlect' => 'Teorlect',
        ];
    }
}
