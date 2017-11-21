<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lecture_table".
 *
 * @property integer $ID
 * @property string $time_start
 * @property string $time_stop
 * @property integer $corps
 *
 * @property Corps $corps0
 */
class LectureTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lecture_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time_start', 'time_stop', 'corps'], 'required'],
            [['time_start', 'time_stop'], 'safe'],
            [['corps'], 'integer'],
            [['corps'], 'exist', 'skipOnError' => true, 'targetClass' => Corps::className(), 'targetAttribute' => ['corps' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'time_start' => 'Time Start',
            'time_stop' => 'Time Stop',
            'corps' => 'Corps',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCorps0()
    {
        return $this->hasOne(Corps::className(), ['ID' => 'corps']);
    }
}
