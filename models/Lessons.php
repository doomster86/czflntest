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
            ['subject_id', 'required', 'message' => 'Обов\'язкове поле.'],
            ['quantity', 'required', 'message' => 'Обов\'язкове поле. Має бути числом.'],
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
            'subject_id' => 'Subject',
            'quantity' => 'Quantity',
        ];
    }
}
