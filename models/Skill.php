<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skill".
 *
 * @property integer $ID
 * @property string $skill
 */
class Skill extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'skill';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['skill'], 'required'],
            [['skill'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'skill' => 'Skill',
        ];
    }
}
