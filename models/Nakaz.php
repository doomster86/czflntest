<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nakaz".
 *
 * @property int $ID
 * @property int $teacher_id
 * @property int $subject_id
 * @property int $rnp_id
 * @property int $column_num
 * @property int $type
 * @property string $title
 */
class Nakaz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nakaz';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'subject_id', 'rnp_id', 'column_num', 'type', 'title'], 'required'],
            [['teacher_id', 'subject_id', 'rnp_id', 'column_num', 'type'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'teacher_id' => 'Teacher ID',
            'subject_id' => 'Subject ID',
            'rnp_id' => 'Rnp ID',
            'column_num' => 'Column Num',
            'type' => 'Type',
            'title' => 'Title',
        ];
    }
}
