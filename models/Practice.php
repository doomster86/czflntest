<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "practice".
 *
 * @property integer $ID
 * @property string $name
 * @property integer $master_id
 * @property integer $max_week
 * @property integer $total
 *
 * @property User $master
 */
class Practice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'master_id', 'max_week'], 'required'],
            [['master_id', 'max_week'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['master_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['master_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'name' => 'Name',
            'master_id' => 'Master ID',
            'max_week' => 'Max Week',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaster()
    {
        return $this->hasOne(User::className(), ['id' => 'master_id']);
    }
}
