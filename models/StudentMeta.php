<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "student_meta".
 *
 * @property integer $id
 * @property integer $group_id
 *
 * @property Groups $group
 */
class StudentMeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id'], 'required'],
            [['group_id'], 'integer'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['group_id' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['ID' => 'group_id']);
    }

    /* Геттер для названия Пед.звання */
    public function getGroupName() {
        return $this->group->name;
    }

    /* Геттер селекта Пед.звання */
    public function  getAllGroups() {
        $groups_values = Groups::find()->asArray()->select('name')->orderBy('ID')->all();
        $groups_values = ArrayHelper::getColumn($groups_values, 'name');

        $groups_ids = Groups::find()->asArray()->select('ID')->orderBy('ID')->all();
        $groups_ids = ArrayHelper::getColumn($groups_ids, 'ID');

        $groups = array_combine($groups_ids, $groups_values);

        return $groups;
    }
}
