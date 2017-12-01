<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "lecture_table".
 *
 * @property integer $ID
 * @property string $time_start
 * @property string $time_stop
 * @property integer $corps_id
 *
 * @property Corps $corps
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
            [['time_start', 'time_stop', 'corps_id'], 'required'],
            [['corps_id'], 'integer'],
            [['time_start', 'time_stop'], 'string', 'max' => 255],
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
            'corps_id' => 'Corps ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCorps()
    {
        return $this->hasMany(Corps::className(), ['ID' => 'corps_id']);
    }

    public function getCorpsName() {
        return $this->corps->name;
    }

    public function getCorpsNames() {

        $corps = $this->getCorps();

        $skill_values = $corps->name;

        $skill_ids = $corps->ID

        return $skill_values;
    }

}
