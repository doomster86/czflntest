<?php

namespace app\models;

use Yii;
use app\models\LectureTable;
use app\models\Audience;

/**
 * This is the model class for table "corps".
 *
 * @property integer $ID
 * @property string $name
 * @property string $location
 */
class Corps extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'corps';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['corps_name', 'location'], 'required','message'=>'Обов\'язкове поле'],
            [['corps_name', 'location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'corps_name' => 'Назва',
            'location' => 'Розташування',
        ];
    }

    public function getLectureTable()
    {
        return $this->hasMany(LectureTable::className(), ['corps_id' => 'ID']);
    }

    public function getLecture($id) {
        $lectureArray = LectureTable::find()->asArray()->select(['ID', 'time_start', 'time_stop'])->where(['corps_id' => $id])->all();
        if(!empty($lectureArray)) {
            return $lectureArray;
        } else {
            return NULL;
        }
    }

    public function getAudience($id) {
        $audienceArray = Audience::find()->asArray()->select(['ID', 'name'])->where(['corps_id' => $id])->all();
        if(!empty($audienceArray)) {
            return $audienceArray;
        } else {
            return NULL;
        }
    }
}
