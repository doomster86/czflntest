<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audience".
 *
 * @property integer $ID
 * @property string $name
 * @property string $num
 * @property integer $corps
 */
class Audience extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'audience';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'num', 'corps'], 'required'], //список обязательных полей
            //[['corps'], 'string'],
            [['name', 'num'], 'string',   'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Назва',
            'num' => 'Номер',
            'corps' => 'Корпус',
        ];
    }
}
