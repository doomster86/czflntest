<?php

namespace app\models;

use Yii;
use app\models\Groups;

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
            ['name', 'unique', 'message'=>'Така професія вже існує'],
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

    public function getGroups($id) {
        $groupsArray = Groups::find()->asArray()->select(['ID', 'name'])->where(['course' => $id])->all();
        if(!empty($groupsArray)) {
            return $groupsArray;
        } else {
            return NULL;
        }
    }
}
