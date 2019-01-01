<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rnps".
 *
 * @property int $ID
 * @property int $prof_id
 */
class Rnps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rnps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prof_id'], 'required'],
            [['prof_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'prof_id' => 'Prof ID',
        ];
    }
}
