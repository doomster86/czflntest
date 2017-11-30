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
            [['corps_id'], 'exist', 'skipOnError' => true, 'targetClass' => Corps::className(), 'targetAttribute' => ['corps_id' => 'ID']],
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
        return $this->hasOne(Corps::className(), ['ID' => 'corps_id']);
    }

    public function getCorpsNames() {

        $rank_values = $this->corps->find()->asArray()->select('name')->orderBy('ID')->all();

        $rank_values = ArrayHelper::getColumn($rank_values, 'name');

        $rank_ids = $this->corps->find()->asArray()->select('ID')->orderBy('ID')->all();
        $rank_ids = ArrayHelper::getColumn($rank_ids, 'ID');

        $ranks = array_combine($rank_ids,$rank_values);

        return $ranks;
    }

}
