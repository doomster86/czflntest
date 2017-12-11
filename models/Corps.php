<?php

namespace app\models;

use Yii;

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


}
