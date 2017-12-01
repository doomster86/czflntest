<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Corps;
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
class LectureTable extends \yii\db\ActiveRecord {


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'lecture_table';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['time_start', 'time_stop', 'corpsName'], 'required'],
            [['corps_id'], 'integer'],
            [['time_start', 'time_stop'], 'string', 'max' => 255],
            [['corps_id'], 'exist', 'skipOnError' => true, 'targetClass' => Corps::className(), 'targetAttribute' => ['corps_id' => 'ID']],
            //[['time_start'], 'in', 'range' => range(5, 20), 'message' => 'Not in range!']
            //[['time_start'], 'myValidate']
        ];
    }
/*
    public function myValidate($attribute,$params) {

    }
*/
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'time_start' => 'Дата початку',
            'time_stop' => 'Дата закінчення',
            'corps_id' => 'Корпус',
	        'corpsName' => 'Корпус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    //связь таблиц
    public function getCorps() {
        return $this->hasOne(Corps::className(), ['ID' => 'corps_id']);
    }

    //для поиска
    public function getCorpsName() {
        return $this->corps->name;
    }

    public function getCorpsNames() {

        $corps_values = Corps::find()->asArray()->select('name')->orderBy('ID')->all();
        $corps_values = ArrayHelper::getColumn($corps_values, 'name');

        $corps_ids = Corps::find()->asArray()->select('ID')->orderBy('ID')->all();
        $corps_ids = ArrayHelper::getColumn($corps_ids, 'ID');

        $corps = array_combine($corps_ids,$corps_values);

        $corps_add = array( 0 => 'Оберіть корпус');
        $corps = ArrayHelper::merge($corps_add, $corps);

        return $corps;
    }

}
