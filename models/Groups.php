<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property integer $ID
 * @property string $name
 * @property integer $course
 * @property integer $curator
 */
class Groups extends \yii\db\ActiveRecord {

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'curator']);
    }

    public function getUserFirstName() {
        return $this->user->firstname;
    }

    public function getUserLastName() {
        return $this->user->lastname;
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'groups';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'course', 'curator'], 'required'],
            [['course', 'curator'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ID' => 'ID',
            'name' => 'Назва',
            'course' => 'Професія',
            'curator' => 'Куратор',
            'userFirstName' => 'Ім\'я куратора',
            'userLastName' => 'Прізвище куратора',
        ];
    }


}
