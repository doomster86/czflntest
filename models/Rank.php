<?php

namespace app\models;

use Yii;
use app\models\TeacherMeta;
use app\models\User;

/**
 * This is the model class for table "rank".
 *
 * @property integer $ID
 * @property string $rank_name
 *
 * @property TeacherMeta[] $teacherMetas
 */
class Rank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rank_name'], 'required', 'message' => 'Обов\'язкове поле'],
            [['rank_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'rank_name' => 'Rank Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherMetas()
    {
        return $this->hasMany(TeacherMeta::className(), ['rank_id' => 'ID']);
    }

    public function getTeachers($id) {
        //$teachersArray = TeacherMeta::find()->asArray()->select(['id'])->where(['rank_id' => $id])->all();
        $teachersArray = Yii::$app->db->createCommand('SELECT user_id, firstname, middlename, lastname, teacher_type FROM teacher_meta LEFT JOIN user ON teacher_meta.user_id = user.id WHERE teacher_meta.rank_id = 4')->queryAll();
        if(!empty($teachersArray)) {
            return $teachersArray;
        } else {
            return NULL;
        }
    }
}
