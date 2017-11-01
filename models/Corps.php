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
            [['name', 'location'], 'required'],
            [['name', 'location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'name' => 'Назва',
            'location' => 'Розташування',
        ];
    }
}
