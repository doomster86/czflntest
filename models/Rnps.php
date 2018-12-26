<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rnps".
 *
 * @property int $ID
 * @property int $course_id
 * @property int $module_id
 */
class Rnps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rnps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'module_id'], 'required'],
            [['course_id', 'module_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'course_id' => 'Course ID',
            'module_id' => 'Module ID',
        ];
    }
}
