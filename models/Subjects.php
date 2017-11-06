<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subjects".
 *
 * @property integer $ID
 * @property string $name
 */
class Subjects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subjects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message'=>'Обовязкове поле'], // обязательные поля
            [['name'], 'string', 'max' => 255],
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
        ];
    }
}
