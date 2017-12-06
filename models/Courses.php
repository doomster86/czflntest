<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "courses".
 *
 * @property integer $ID
 * @property string $name
 * @property string $subject
 */
class Courses extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'courses';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['name', 'required', 'message'=>'Обов\'язкове поле'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'name' => 'Name',

        ];
    }
    
}
