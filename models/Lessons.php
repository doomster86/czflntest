<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lessons".
 *
 * @property integer $ID
 * @property integer $course_id
 * @property string $course_name
 * @property string $subject
 * @property integer $quantity
 */
class Lessons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lessons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'course_name', 'subject', 'quantity'], 'required'],
            [['course_id', 'quantity'], 'integer'],
            [['course_name', 'subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'course_id' => 'Course ID',
            'course_name' => 'Course Name',
            'subject' => 'Subject',
            'quantity' => 'Quantity',
        ];
    }
}
