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

    public function getUserName() {
        return $this->user->firstname . ' ' . $this->user->lastname;
    }

	public function getCourses() {
    	return $this->hasOne(Courses::className(), ['ID' => 'course']);
	}

	public function getCoursesName() {
    	return $this->courses->name;
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
            'coursesName' => 'Професія',
	        'curator' => 'Куратор',
            'userName' => 'Ім\'я куратора',
        ];
    }


}
